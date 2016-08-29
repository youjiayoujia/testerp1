<?php

namespace App\Http\Controllers\Publish\Smt;

use App\Http\Controllers\Controller;
use App\Models\Publish\Smt\smtProductSku;
use App\Models\Publish\Smt\smtProductGroup;
use App\Models\Publish\Smt\smtProductDetail;
use App\Models\Publish\Smt\smtProductList;
use App\Models\Publish\Smt\smtFreightTemplate;
use App\Models\Publish\Smt\smtServiceTemplate;
use App\Models\Publish\Smt\smtTemplates;
use App\Models\Publish\Smt\smtProductModule;
use Illuminate\Support\Facades\Input;
use App\Models\Channel\AccountModel;
use App\Modules\Channel\ChannelModule;
use App\Models\ChannelModel;
use App\Modules\Channel\Adapter\AliexpressAdapter;



class SmtProductController extends Controller
{
    private $_product_statues_type = array(
        "onSelling",
        "offline",
        "auditing",
        "editingRequired"
    ); // 商品业务状态
    
    public function __construct(){
        $this->model = new smtProductList();
        $this->Smt_product_detail_model = new smtProductDetail();
        $this->Smt_product_skus_model = new smtProductSku();
        $this->Smt_product_group_model = new smtProductGroup();
        $this->Smt_freight_template_model = new smtFreightTemplate();
        $this->Smt_service_template_model = new smtServiceTemplate();
        $this->Smt_template_model = new smtTemplates();
        $this->Smt_product_model = new smtProductModule();
        $this->viewPath = 'publish.smt.';
        $this->channel_id = ChannelModel::where('driver','aliexpress')->first()->id;
    }
    
    public function index()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'mixedSearchFields' => $this->model->mixed_search,
        ];    
        return view($this->viewPath . 'relation_List', $response);
    }
    
    /**
     * 同步产品分组
     */
    public function getProductGroup()
    {
        $token_id = Input::get('token_id');
        $return   = Input::get('return'); //用以判断需返回的数据
        $selected = Input::get('selected'); //选中的项
        if ($token_id) {
            $token_info  = AccountModel::findOrFail($token_id);
            $token_array = array($token_info);
        } else {           
            $token_array      = AccountModel::where('channel_id',$this->channel_id)->get();
        
        }          
        $flag = false; //是否同步成功
        $ChannelModule = new ChannelModule();
        
        foreach ($token_array as $account) {   
            $smtApi = $ChannelModule->driver($account->channel->driver, $account->api_config);
            $data = $this->getProductGroupList($smtApi);
          
            if ($data) {                
                //先变更成过期的再说
                $oneData['last_update_time'] = date('Y-m-d H:i:s', strtotime('-2 day'));
                //$this->Smt_product_group_model->where('token_id',$token_id)->update($oneData);
                foreach ($data as $row) {  
                    $options['token_id']         = $token_id;
                    $options['group_id']         = $row['groupId'];
                    $options['group_name']       = trim($row['groupName']);
                    $options['last_update_time'] = date('Y-m-d H:i:s');
                    //判断产品分组是否存在
                    $id = $this->Smt_product_group_model->where(['token_id'=>$token_id,'group_id'=>$row['groupId']])->first();
                    if (!$id) { //不存在插入
                        $this->Smt_product_group_model->create($options);
                    } else { //存在就变更
                        $options['id'] = $id;
                        $this->Smt_product_group_model->update($options);
                    }
                    if (array_key_exists('childGroup', $row)) { //含有子分组
                        foreach ($row['childGroup'] as $child) {
                            $rs['token_id']         = $token_id;
                            $rs['group_id']         = $child['groupId'];
                            $rs['group_name']       = trim($child['groupName']);
                            $rs['parent_id']        = $row['groupId'];
                            $rs['last_update_time'] = date('Y-m-d H:i:s');

                            $cid = $this->Smt_product_group_model->where(['token_id'=>$token_id,'group_id'=>$child['groupId']])->first();
                            if (!$cid) {
                                $this->Smt_product_group_model->create($rs);
                            } else {
                                $rs['id'] = $cid;
                                $this->Smt_product_group_model->update($rs);
                            }
                            unset($rs);
                            unset($cid);
                        }
                    }
                    unset($options);
                    unset($id);
                }
                //删除过期的模板
                $this->Smt_product_group_model->where('token_id',$token_id)->where('last_update_time','<',time())->delete();
                $flag = true;//同步成功
            }
            unset($data);
        }
        unset($token_array);
        if ($token_id && $flag && $return == 'data'){
            $group_list = $this->getLocalProductGroupList($token_id);         
            $options = '';
            if ($group_list){
                foreach ($group_list as $group){                      
                    if (array_key_exists('child', $group)) {   
                        $options .= '<optgroup label="'.$group['group_name']  .'">';                     
                        foreach($group['child'] as $r):                    
                            $options .= '<option value="'.$r['group_id'].'" '.($selected == $r['group_id'] ? 'selected="selected"' : '').'>&nbsp;&nbsp;&nbsp;&nbsp;--'.$r['group_name'].'</option>';
                        endforeach;
                        $options .= '</optgroup>';
                    }else {                 
                        $options .= '<option value="'.$group['group_id'].'">'.$group['group_name'].'</option>';
                    }
                }
            }
            $this->ajax_return('', true, $options);
        }else {
            $this->ajax_return('产品分组同步'.($flag ? '成功' : '失败'), $flag);
        }
    }
    
    /**
     * 同步速卖通运费模板信息
     */
    public function getFreightTemplateList()
    {
        $token_id = Input::get('token_id');
        $return   = Input::get('return'); 
        $selected = Input::get('selected');
        if ($token_id) {
            $token_info  = AccountModel::findOrFail($token_id);
            $token_array = array($token_info);
        } else {           
            $token_array      = AccountModel::where('channel_id',$this->channel_id)->get();
        
        }          
        $flag = false; //是否同步成功

        $ChannelModule = new ChannelModule();    
        foreach ($token_array as $t) {
            $smtApi = $ChannelModule->driver($t->channel->driver, $t->api_config);    
            $freight_list = $this->getOnlineFreightTemplateList($smtApi);
            if ($freight_list) { //模板信息
                foreach ($freight_list as $row) {
                    $freight_info = $this->getFreightSettingByTemplateQuery($smtApi,$row['templateId']);
                    $options      = array(
                        'token_id'           => $t['id'],
                        'templateId'         => trim($row['templateId']),
                        'templateName'       => trim($row['templateName']),
                        'default'            => ($row['default'] ? 1 : 0),
                        'freightSettingList' => serialize($freight_info['freightSettingList']),
                        'last_update_time'   => date('Y-m-d H:i:s')
                    );
                    $Info = $this->Smt_freight_template_model->where(['token_id'=>$t['id'],'templateId'=>$row['templateId']])->first();                   
                    if (!$Info) {
                        $this->Smt_freight_template_model->create($options);
                    } else {//更新下数据
                        $options['id'] = $Info['id'];
                        $this->Smt_freight_template_model->update($options);
                    }    
                    unset($freight_info);
                    unset($id);
                    unset($options);
                }
                //删除本地过期的运费模板
                $this->Smt_freight_template_model->where('token_id',$token_id)->where('last_update_time','<',time())->delete();
                $flag = true;
            }
            unset($freight_list);
        }
        unset($token_array);
        if ($token_id && $flag && $return == 'data'){ //返回查询的数据，先决条件是同步成功
            $template_list = $this->getLocalFreightTemplateList($token_id);
            $options = '';
            if ($template_list){
                foreach ($template_list as $template){
                    $options .= '<option value="'.$template['templateId'].'" '.($template['templateId'] == $selected ? 'selected="selected"' : '').'>'.$template['templateName'].'</option>';
                }
            }
            unset($template_list);
            $this->ajax_return('', true, $options);
        }else {
            $this->ajax_return('运费模板同步' . ($flag ? '成功' : '失败'), $flag);
        }
    }
    
    /**
     * 同步产品服务模板
     * @return [type] [description]
     */
    public function getServiceTemplateList()
    {
        $token_id = Input::get('token_id');
        $return   = Input::get('return'); 
        $selected = Input::get('selected');
        if ($token_id) {
            $token_info  = AccountModel::findOrFail($token_id);
            $token_array = array($token_info);
        } else {           
            $token_array      = AccountModel::where('channel_id',$this->channel_id)->get();
        
        }          
        $flag = false; //是否同步成功
        $ChannelModule = new ChannelModule();   
        foreach ($token_array as $t) {
            $smtApi = $ChannelModule->driver($t->channel->driver, $t->api_config);
            $data = $this->queryPromiseTemplateById($smtApi);
            if ($data) {
                //先把数据变更成过期的
                $oneData['last_update_time'] = date('Y-m-d H:i:s', strtotime('-2 day'));
                $this->Smt_service_template_model->where('token_id', $t['id'])->update($oneData);
                foreach ($data as $row) {
                    $options['token_id']         = $t['id'];
                    $options['serviceID']        = $row['id'];
                    $options['serviceName']      = trim($row['name']);
                    $options['last_update_time'] = date('Y-m-d H:i:s');
    
                    $Info = $this->Smt_service_template_model->where(['token_id'=>$t['id'],'serviceID'=>$row['id']])->first();
                    if (!$Info) { //不存在就插入
                        $this->Smt_service_template_model->create($options);
                    } else { //存在就UPDATE
                        $options['id'] = $Info['id'];
                        $this->Smt_service_template_model->update($options);
                    }
                    unset($options);
                    unset($id);
                }
                //删除过期未同步的模板
                $this->Smt_service_template_model->where('token_id',$token_id)->where('last_update_time','<',time())->delete();
                $flag = true;
            }
            unset($data);
        }
        unset($token_array);
    
        if ($token_id && $flag && $return == 'data'){
            $template_list = array();
            $result = $this->Smt_service_template_model->where('token_id',$token_id)->get();
            if ($result) {
                foreach ($result as $row) {
                    $template_list[$row['serviceID']] = $row;
                }
            }
            $options = '';
            if ($template_list){
                foreach ($template_list as $temp){
                    $options .= '<option value="'.$temp['serviceID'].'" '.($selected == $temp['serviceID'] ? 'selected="selected"' : '').'>'.$temp['serviceName'].'</option>';
                }
            }
            $this->ajax_return('', true, $options);
        }else {
            $this->ajax_return('服务模板同步' . ($flag ? '成功' : '失败'), $flag);
        }
    }
    
    /**
     * 异步获取平台对应的模板
     */
    public function ajaxGetPlatTemplateList(){
        //平台ID
        $plat = Input::get('plat');
    
        if ($plat){
            $data = $this->Smt_template_model->where('plat',$plat)->get();
            $options = '';
            if ($data){
                foreach ($data as $row){
                    $options .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                }
            }
            unset($data);
            $this->ajax_return('', true, $options);
        }else {
            $this->ajax_return('平台错误', false);
        }
    }
    
    /**
     * 获取在线产品分组
     * @param AliexpressAdapter $smtApi
     * @return [type] [description]
     */
    public function getProductGroupList(AliexpressAdapter $smtApi)
    {
        $api    = 'api.getProductGroupList';
        $result = $smtApi->getJsonData($api, '', true);
        $data   = json_decode($result, true);
        return $data['success'] ? $data['target'] : false;
    }
    
    /**
     * 获取线上运费模板列表
     * @param AliexpressAdapter $smtApi
     * @return [type] [description]
     */
    public function getOnlineFreightTemplateList(AliexpressAdapter $smtApi)
    {
        $api    = 'api.listFreightTemplate';
        $result = $smtApi->getJsonData($api, '', true); //现在需要签名了
        $data   = json_decode($result, true);    
        return $data['success'] ? $data['aeopFreightTemplateDTOList'] : false;
    }
    
   /**
    * 获取运费模板详情
    * @param AliexpressAdapter $smtApi
    * @param int $templateId
    */
    public function getFreightSettingByTemplateQuery(AliexpressAdapter $smtApi,$templateId)
    {
        $api    = 'api.getFreightSettingByTemplateQuery';
        $result = $smtApi->getJsonData($api, 'templateId=' . $templateId);
        $data   = json_decode($result, true);
        return $data['success'] ? $data : false;
    }
    
    /**
     * 获取产品服务模板
     * @param AliexpressAdapter $smtApi
     * @param int $templateId
     */
    public function queryPromiseTemplateById(AliexpressAdapter $smtApi,$templateId = -1)
    {
        $api = 'api.queryPromiseTemplateById';
    
        $result = $smtApi->getJsonData($api, 'templateId=' . $templateId, true);
        $data   = json_decode($result, true);
    
        return $data['templateList'] ? $data['templateList'] : false;
    }
    
    /**
     * 获取账号的产品分组列表并组装成原获取的数据格式
     * @param  [type] $token_id 账号ID
     * @return [type]           [description]
     */
    public function getLocalProductGroupList($token_id){
        $result = $this->Smt_product_group_model->where('token_id',$token_id)->get()->toArray();
        $rs = array();
        if($result){
            foreach ($result as $row) {
                if ($row['parent_id'] == '0') { //说明是一级产品分组
                    $rs[$row['group_id']] = $row;
                }else {
               
                    $rs[$row['parent_id']]['child'][] = $row;
                }
            }
        }
        return $rs;
    }
    
    /**
     * 获取帐号的运费模版列表
     * @param number $token_id
     * @return multitype:unknown
     */
    public function getLocalFreightTemplateList($token_id = 0){
        $res = array();
        $result = $this->Smt_freight_template_model->where('token_id',$token_id)->get();
        if ($result) {
            foreach ($result as $row) {
                $res[$row['templateId']] = $row;
            }
        }
        return $res;
    }
    
    /**
     * 选择产品列表(用于关联产品)
     */
    public function selectRelationProducts(){
    
        //速卖通广告状态
        $smt_product_status = $this->_product_statues_type;
        $where    = array(); //查询条件
        $in       = array(); //in查询条件
        $string   = array(); //URL参数
        $curpage  = 40;
        $per_page = (int)Input::get('per_page');
        $group_id = Input::get('groupId');
        $token_id = Input::get('token_id');
        $productId = trim(Input::get('productId'));
        $productStatusType = Input::get('productStatusType');
        $sku = trim(Input::get('sku'));
        $subject = trim(Input::get('subject'));    
    
        $isRemove = 0; //默认没被删除的
    
        $group_list = array(); //产品分组列表      
        //有选择账号，选下分组查询分组出来
        $group_list = smtProductGroup::where('token_id',$token_id)->first();
        if($group_list['id']){
            $re = array();
            foreach ($group_list as $row) {
                if ($row['parent_id'] == '0') { //说明是一级产品分组
                	$rs[$row['group_id']] = $row;
                }else {
                	$rs[$row['parent_id']]['child'][] = $row;
                }
                $group_list = $rs;
            }
        }
    
        if (isset($group_id) && $group_id != ''){ //分组信息
            if ($group_id == 'none'){
                $where['groupId'] = 0;
            }else {
                //查询是否有子分组
                $child_group = array(); //子分组ID
                if (!empty($group_list[$group_id]['child'])){ //说明是有子分组的
                    foreach ($group_list[$group_id]['child'] as $row){
                        $child_group[] = $row['group_id'];
                    }
                }
                array_push($child_group, $group_id);
    
                $in['groupId'] = $child_group;
            }
        }
        $string['groupId'] = $group_id;
    
        if (!empty($productId)) {
            $where['productId'] = $productId;
            $string['productId']           = $productId;
        }
        if (!empty($productStatusType)) {
            if ($productStatusType == 'other'){
                $isRemove = 1; //查询被删除的
            }else {
                $where['productStatusType'] = $productStatusType;
            }
            $string['productStatusType']                   = $productStatusType;
        }else {
            $in['productStatusType'] = $this->_product_statues_type;
        }
        if (!empty($sku)) { //按SKU查询
            $product_ids = $this->Smt_product_skus_model->where('skuCode','like','%'.$sku.'%')->groupby('productId')->distinct()->lists('productId');
            if ($product_ids) {
                $in['productId'] = $product_ids;
            } else {
                $in['productId'] = '0';
            }
            $string['sku'] = $sku;
        }
        $like = array();   
    
    
        $where['isRemove'] = $isRemove; //默认只查询没被删除的       
        // 新增的标题LIKE 查询
        if (!empty($subject)) { //按SKU查询
            $like['subject'] = $subject;
            $options['like']=$like;
            $string['subject'] = $subject;
        }

        $data_list   = $this->model->where($where)->lists('productId, subject, productStatusType');
    
        //var_dump($data_list);exit;
    
        //读取SKU出来
        $product_arr = array();
        foreach ($data_list as $item) {
            $product_arr[] = $item->productId;
        }   
                   
        $detail_list = $this->Smt_product_detail_model->whereIn('productId',$product_arr)->lists('productId,imageURLs');
    
        $response = [
            'smt_product_status' => $smt_product_status,
            'data_list'          => $data_list,     
            'detail_list'        => $detail_list,
            'group_list'         => $group_list,
            ];
        return view('publish.smt.relation_list',$response);
    }
    
    //json返回数据结构
    public function ajax_return($info='', $status=1, $data='') {
        $result = array('data' => $data, 'info' => $info, 'status' => $status);
        exit( json_encode($result) );
    }
    
    /**
     * 批量修改产品
     */
    public function batchModifyProducts(){
        $productIds = request()->input('operateProductIds');
        $from       = request()->input('from'); //判断数据来源
        
        $productList = array();
        $productDetail = array();
        if (!empty($productIds)) { //产品ID非空
        
            //获取产品信息并显示出来(图片，标题，关键词，单位，重量，尺寸，产品信息模块，服务模板，运费模板，零售价，产品id，分类id)
            $productIdArr = explode(',', $productIds);        
            $product_info = $this->model->whereIn('productId',$productIdArr)->get();
            $token_id = 0;
            if(count($product_info)){
                $temp = array();
                foreach($product_info as $row){                                     
                    $temp[] = $row->token_id;                                    
                }
                $temp = array_unique($temp);
                $token_id =  count($temp) == 1 ? array_shift($temp) : false;
            }
            if (!$token_id){
                $data = array(
                    'error' => '选择的产品无账号或不在同一个账号，请重新选择'
                );
            }else {
                dd($productList);
                $productList   = $product_info->toArray();
                $productDetail = $productList->details->toArray();
        
        
                
                $freightList = smtFreightTemplate::where('token_id',$token_id)->get();
                 
                //服务模板
                $serveList = smtServiceTemplate::where('token_id',$token_id)->get();
                
                //产品分组
                $product_group = new SmtProductController();
                $groupList = $product_group->getLocalProductGroupList($token_id);                
                //单位列表
                $unitList = $this->smtProductUnitModel->getAllUnit();                                                                                 
        
                $data = array( //传过去的数据
                    'productIds'    => $productIds,
                    'productList'   => $productList,
                    'productDetail' => $productDetail,
                    'unitList'      => $unitList,
                    'freightList'   => $freightList,
                    'serveList'     => $serveList,
                    'groupList'     => $groupList,
                    'token_id'      => $token_id,
                    'from'          => $from
                );
            }
        }else {
            $data = array(
                'error' => '请先选择要修改的产品'
            );
        }
     
        return view($this->viewPath . 'smtProduct/batch_modify', $data);
    }
    
    
}
