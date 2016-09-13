<?php

namespace App\Http\Controllers\Publish\Smt;

use Channel;
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
use App\Models\ChannelModel;
use App\Modules\Channel\Adapter\AliexpressAdapter;
use App\Models\Publish\Smt\smtProductUnit;
use App\Models\Publish\Smt\smtUserSaleCode;
use Illuminate\Support\Facades\DB;


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
        $this->smt_product_unit_model = new smtProductUnit();
        $this->viewPath = 'publish.smt.';
        $this->mainIndex = route('smt.index');
        $this->mainTitle = 'SMT产品草稿';
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
        
        foreach ($token_array as $account) {   
            $smtApi = Channel::driver($account->channel->driver, $account->api_config);
            $account_id = $account->id;
            $data = $this->getProductGroupList($smtApi);
            if ($data) {                
                //先变更成过期的再说
                $oneData['last_update_time'] = date('Y-m-d H:i:s', strtotime('-2 day'));
                $this->Smt_product_group_model->where('token_id',$account_id)->update($oneData);
                foreach ($data as $row) {  
                    $options['token_id']         = $account_id;
                    $options['group_id']         = $row['groupId'];
                    $options['group_name']       = trim($row['groupName']);
                    $options['last_update_time'] = date('Y-m-d H:i:s');
                    //判断产品分组是否存在
                    $group_info = $this->Smt_product_group_model->where(['token_id'=>$account_id,'group_id'=>$row['groupId']])->first();
                    if (!$group_info) { //不存在插入
                        $this->Smt_product_group_model->create($options);
                    } else { //存在就变更
                        $id = $group_info->id;
                        $this->Smt_product_group_model->where('id',$id)->update($options);
                    }
                    if (array_key_exists('childGroup', $row)) { //含有子分组
                        foreach ($row['childGroup'] as $child) {
                            $rs['token_id']         = $account_id;
                            $rs['group_id']         = $child['groupId'];
                            $rs['group_name']       = trim($child['groupName']);
                            $rs['parent_id']        = $row['groupId'];
                            $rs['last_update_time'] = date('Y-m-d H:i:s');

                            $child_group = $this->Smt_product_group_model->where(['token_id'=>$account_id,'group_id'=>$child['groupId']])->first();
                            if (!$child_group) {
                                $this->Smt_product_group_model->create($rs);
                            } else {
                                $cid = $child_group->id;
                                $this->Smt_product_group_model->where('id',$cid)->update($rs);
                            }
                            unset($rs);
                            unset($cid);
                        }
                    }
                    unset($options);
                    unset($id);
                    //删除过期的模板
                    $this->Smt_product_group_model->where('token_id',$account_id)->where('last_update_time','<',time())->delete();
                    $flag = true;//同步成功
                }              
            }
            unset($data);
        }
        unset($token_array);
        if ($token_id && $flag && $return == 'data'){
            $group_list = $this->getLocalProductGroupList($account_id);         
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
    
        foreach ($token_array as $t) {
            $smtApi = Channel::driver($t->channel->driver, $t->api_config);    
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
                        $this->Smt_freight_template_model->where('id',$Info['id'])->update($options);
                    }    
                    unset($freight_info);
                    unset($id);
                    unset($options);
                }
                //删除本地过期的运费模板
                $this->Smt_freight_template_model->where('token_id',$t['id'])->where('last_update_time','<',time())->delete();
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
        foreach ($token_array as $t) {
            $smtApi = Channel::driver($t->channel->driver, $t->api_config);
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
                    if (!$Info) {
                        $this->Smt_service_template_model->create($options);
                    } else {                       
                        $this->Smt_service_template_model->where('id',$Info['id'])->update($options);
                    }
                    unset($options);
                    unset($id);
                }
                //删除过期未同步的模板
                $this->Smt_service_template_model->where('token_id',$t['id'])->where('last_update_time','<',time())->delete();
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
        return isset($data['success']) ? $data['target'] : false;
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
        return isset($data['success']) ? $data['aeopFreightTemplateDTOList'] : false;
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
        return isset($data['success']) ? $data : false;
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
    
        return isset($data['templateList']) ? $data['templateList'] : false;
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
                $productList   = $product_info;                                                  
                $product_group = new SmtProductController();    //产品分组
                $groupList = $product_group->getLocalProductGroupList($token_id); 
                $freightList = $product_group->getLocalFreightTemplateList($token_id);                              
                $unitList = $this->smt_product_unit_model->getAllUnit();       //单位列表                                                                            
        
                $data = array( //传过去的数据
                    'productIds'    => $productIds,
                    'productList'   => $productList,                   
                    'unitList'      => $unitList,
                    'freightList'   => $freightList,                    
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
    
    public function batchModifyProduct(){
        if(request()->input('type') == 'waitPost'){
            $this->mainTitle = "待发布产品列表";
            $this->mainIndex = route("smt.waitPost");
        }
       
        $productIds = request()->input('ids');
       
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
                    return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '选择的产品无账号或不在同一个账号，请重新选择.'));
            }else {
                $productList   = $product_info;
                $product_group = new SmtProductController();    //产品分组
                $groupList = $product_group->getLocalProductGroupList($token_id);
                $freightList = $product_group->getLocalFreightTemplateList($token_id);
                $serveList = $this->Smt_service_template_model->getServiceTemplateList($token_id);
                $unitList = $this->smt_product_unit_model->getAllUnit();       //单位列表
    
                $response = array( //传过去的数据
                    'metas' => $this->metas(__FUNCTION__),                    
                    'productIds'    => $productIds,
                    'productList'   => $productList,
                    'unitList'      => $unitList,
                    'freightList'   => $freightList,
                    'serveList'     => $serveList,
                    'groupList'     => $groupList,
                    'token_id'      => $token_id,
                );
            }
        }else {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '请先选择要修改的产品.'));            
        }
    
        return view($this->viewPath . 'smtProduct/batch_modify', $response);
    }
    
    /**
     * 根据产品id同步线上数据
     */
    public function synchronizationProduct(){
        $productId = trim(request()->input('product_id'));
         
        if ($productId){
            $this->_handleSynchronizationData($productId);
        }else {
            $this->ajax_return('产品ID:'.$productId.'不存在', false);
        }
    }
    
    /**
     * 同步产品数据到erp
     * @param $productId 产品ID
     * @param string $isDieOut
     */
    public function _handleSynchronizationData($productId,$isDieOut=true){
        $account_id = $this->model->where('productId',$productId)->first()->token_id;
        $account = AccountModel::find($account_id);
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        
        $productInfo = $channel->findAeProductById($productId);
        $smtProductsObj = new SmtProductsController();
        if(array_key_exists('success',$productInfo) && $productInfo['success']){
            $product['product_url'] = 'http://www.aliexpress.com/item/-/' . $productInfo['productId'] . '.html';   
            $product['productId'] = $productInfo['productId'];
            $product['token_id'] = $account_id;
            $product['subject'] = array_key_exists('subject', $productInfo) ? $productInfo['subject'] : '';
            $product['productPrice'] = array_key_exists('productPrice',$productInfo) ? $productInfo['productPrice'] : '';
            $product['productStatusType'] = $productInfo['productStatusType'];
            $product['ownerMemberId'] = $productInfo['ownerMemberId'];
            $product['ownerMemberSeq'] = $productInfo['ownerMemberSeq'];
            $product['wsOfflineDate'] = $smtProductsObj->parseDateString($productInfo['wsOfflineDate']);
            $product['wsDisplay'] = array_key_exists('wsDisplay', $productInfo) ? $productInfo['wsDisplay'] : '';
            $product['groupId'] = array_key_exists('groupId', $productInfo) ? $productInfo['groupId'] : '';
            $product['categoryId'] = $productInfo['categoryId'];
            $product['packageLength'] = $productInfo['packageLength'];
            $product['packageWidth'] = $productInfo['packageWidth'];
            $product['packageHeight'] = $productInfo['packageHeight'];
            $product['grossWeight'] = $productInfo['grossWeight'];
            $product['deliveryTime'] = $productInfo['deliveryTime'];
            $product['wsValidNum'] = $productInfo['wsValidNum'];
            //$product['synchronizationTime'] = date('Y-m-d H:i:s'); //同步时间
            $product['isRemove']            = '0';
            $product['multiattribute'] = count($productInfo['aeopAeProductSKUs']) > 1 ? 1 : 0;
            
            $tempSKU = $productInfo['aeopAeProductSKUs'][0];
            $minPrice = $tempSKU['skuPrice'];
            $maxPrice = 0;
            $user_id = '';
            //获取销售前缀
            $sale_prefix = $smtProductsObj->get_skucode_prefix($tempSKU['skuCode']);
            if ($sale_prefix) {
                $userInfo = smtUserSaleCode::where('sale_code', $sale_prefix)->first();
                if ($userInfo) {
                    $user_id = $userInfo->user_id;
                }
            }
            $product['user_id'] = $user_id;
            
            $isExists = false;
            $local_product_info = $this->model->where('productId',$productInfo['productId'])->first();
            if($local_product_info){
                $isExists = true;            
            }
            
            $local_sku_list = array();
            $local_sku_list = $this->Smt_product_skus_model->where('productId',$productInfo['productId'])->lists('smtSkuCode')->toArray();
          
            $online_sku_list = array();
            foreach($productInfo['aeopAeProductSKUs'] as $sku_list){
                $online_sku_list[] = strtoupper(trim($sku_list['skuCode']));
            }            
      
            $deletedSmtSkuList = array_diff($local_sku_list, $online_sku_list);
            if($deletedSmtSkuList){
                $this->Smt_product_skus_model->where('productId',$productInfo['productId'])->whereIn('smtSkuCode',$deletedSmtSkuList)->delete();
            }            
            
            foreach ($productInfo['aeopAeProductSKUs'] as $skuItem) {
                //根据属性值来判断是不是属于海外仓 --海外仓的产品SKU可能还是会一样的
                $valId = $smtProductsObj->checkProductSkuAttrIsOverSea($skuItem['aeopSKUProperty']);
                $skuData = array();
                $skuData['aeopSKUProperty'] = serialize($skuItem['aeopSKUProperty']);
                $sku_arr = $smtProductsObj->_buildSysSku(trim($skuItem['skuCode']));
                if ($sku_arr) {
                    foreach ($sku_arr as $sku_new) {                  
                        $maxPrice = $maxPrice > $sku_list['skuPrice'] ? $maxPrice : $sku_list['skuPrice'];
                        $minPrice = $minPrice < $sku_list['skuPrice'] ? $maxPrice : $sku_list['skuPrice'];
                        
                        $skuData['skuCode'] = $sku_new;
                        $skuData['skuMark'] = $productInfo['productId'] . ':' . $sku_new;
                        $skuData['smtSkuCode'] = $skuItem['skuCode'];
                        $skuData['skuPrice'] = $skuItem['skuPrice'];
                        $skuData['ipmSkuStock'] = $skuItem['ipmSkuStock'];
                        $skuData['productId'] = $productInfo['productId'];
                        $skuData['sku_active_id'] = $skuItem['id'];
                        $tempSKUProperty = $skuItem['aeopSKUProperty'];
                        $aeopSKUProperty = array_shift($tempSKUProperty);
                        $skuData['propertyValueId'] = isset($aeopSKUProperty['propertyValueId']) ? $aeopSKUProperty['propertyValueId'] : 0;
                        $skuData['skuPropertyId'] = isset($aeopSKUProperty['skuPropertyId']) ? $aeopSKUProperty['skuPropertyId'] : 0;
                        $skuData['propertyValueDefinitionName'] =  isset($aeopSKUProperty['propertyValueDefinitionName']) ? $aeopSKUProperty['propertyValueDefinitionName'] : '';
                        $skuData['synchronizationTime'] = date('Y:m:d H:i:s', time());
                        $skuData['updated'] = 1;
                        $skuData['overSeaValId'] = $valId;                      
                        
                        $skuInfo = smtProductSku::where(['smtSkuCode' => $skuItem['skuCode'], 'productId' => $productInfo['productId']])->first();
                        if ($skuInfo) {
                            smtProductSku::where(['smtSkuCode' => $skuItem['skuCode'], 'productId' => $productInfo['productId']])->update($skuData);
                        } else {
                            smtProductSku::create($skuData);
                        }
                    }
                }
            }
            
            $product['productMinPrice']     = $minPrice;
            $product['productMaxPrice']     = $maxPrice;
            
            if ($isExists) {
               $this->model->where('productId',$productInfo['productId'])->update($product);
            } else {            
                 $this->model->create($product);
            }
            
            $productDetail['productId'] = $productInfo['productId'];
            $productDetail['aeopAeProductPropertys'] = serialize($productInfo['aeopAeProductPropertys']);
            $productDetail['imageURLs'] = $productInfo['imageURLs'];
            $productDetail['detail'] = array_key_exists('detail', $productInfo) ? $productInfo['detail'] : '';
            $productDetail['productUnit'] = $productInfo['productUnit'];
            $productDetail['isImageDynamic'] = $productInfo['isImageDynamic'] ? 1 : 0;
            $productDetail['isImageWatermark'] = array_key_exists('isImageWatermark', $productInfo) ? ($productInfo['isImageWatermark'] ? 1 : 0) : 0;
            $productDetail['lotNum'] = $productInfo['lotNum'];
            $productDetail['bulkOrder'] = array_key_exists('bulkOrder', $productInfo) ? $productInfo['bulkOrder'] : 0;
            $productDetail['packageType'] = $productInfo['packageType'];
            $productDetail['isPackSell'] = $productInfo['isPackSell'] ? 1 : 0;
            $productDetail['promiseTemplateId'] = $productInfo['promiseTemplateId'];
            $productDetail['freightTemplateId'] = $productInfo['freightTemplateId'];
            $productDetail['sizechartId'] = array_key_exists('sizechartId', $productInfo) ? $productInfo['sizechartId'] : 0;
            $productDetail['src'] = array_key_exists('src', $productInfo) ? $productInfo['src'] : '';
            $productDetail['bulkDiscount'] = array_key_exists('bulkDiscount', $productInfo) ? $productInfo['bulkDiscount'] : 0;
            
            //判断产品详情是否存在
            $detailIsExists = $this->Smt_product_detail_model->where('productId',$productInfo['productId'])->first();
            if($detailIsExists){
                $this->Smt_product_detail_model->where('productId',$productInfo['productId'])->update($productDetail);
            }else{
                $this->Smt_product_detail_model->create($productDetail);
            }
            
            if ($isDieOut) {
                $this->ajax_return('产品:' . $productId . '同步成功', true);
            }else {
                return array('status' => true, 'info' => '产品:' . $productId . '同步成功');
            }
        }else {
            if ($isDieOut) {
                $this->ajax_return($productInfo['error_message'], false);
            }else {
                return array('status' => false, 'info' => $productInfo['error_message']);
            }
        }
         
    }
    
    /**
     * 复制产品到模板时显示的账号
     * @return Ambigous <\Illuminate\View\View, \Illuminate\Contracts\View\Factory>
     */
    public function showAccountToCopyProduct(){
        $account_list = array();
        $result = AccountModel::where('channel_id',$this->channel_id)->get();
        if ($result) {
            foreach ($result as $r) {
                $account_list[$r['id']] = $r;
            }
        }
       
       $data =  array('account_list' => $account_list);
       return view($this->viewPath . 'account_copy_product', $data);
    }
    
    /**
     * 复制广告成为草稿
     */
    public function copyToDraft(){
        
        $productIds = request()->input('productIds');  //产品ID列表   
        $tokenIds =  request()->input('tokenIds');        //账号列表
        
        $product_array = explode(',', $productIds);
        $token_array   = explode(',', $tokenIds);
        $flag  = false; //标识
        $error = array();
        foreach ($product_array as $productId) {
            $list_info = $this->model->where('productId',$productId)->first();        
            if ($list_info) {
                $detail_info = $list_info->details;    //商品详情
                $sku_info = $list_info->productSku;    //商品的SKU信息
                //各账号循环插入数据 --插入数据的时候，如果图片要保存的话，要把原tokenID保存下来
                foreach ($token_array as $token_id) {    
                    $account = AccountModel::findOrFail($token_id);
                    $smtApi = Channel::driver($account->channel->driver, $account->api_config);
                    /*********插入到草稿主表数据开始*********/
                    $newProductId = $list_info['productId'].'-'.$token_id.'-'.rand(10000, 99999);
                    $draft_product['token_id']      = $token_id;
                    $draft_product['old_token_id']  = $list_info['token_id'];
                    $draft_product['user_id'] = request()->user()->id;
                    $draft_product['subject']       = $list_info['subject'];
                    $draft_product['productPrice']  = $list_info['productPrice'];
                    $draft_product['groupId']       = $list_info['groupId'];
                    $draft_product['categoryId']    = $list_info['categoryId'];
                    $draft_product['packageLength'] = $list_info['packageLength'];
                    $draft_product['packageWidth']  = $list_info['packageWidth'];
                    $draft_product['packageHeight'] = $list_info['packageHeight'];
                    $draft_product['grossWeight']   = $list_info['grossWeight'];
                    $draft_product['deliveryTime']  = $list_info['deliveryTime'];
                    $draft_product['wsValidNum']    = $list_info['wsValidNum'];
                    $draft_product['productStatusType']  = 'newData';
                    $draft_product['old_productId'] = $list_info['productId'];
                    $draft_product['productId']     = $newProductId;
                    /*********插入到草稿主表数据结束*********/
                    DB::beginTransaction();
                    $id = $this->model->create($draft_product);        
                    if (!$id) {
                        $error[] = $list_info['productId'] . ',tokenId:' . $token_id . '复制错误';
                        DB::rollback();
                        continue;
                    }
        
                    /***************插入到草稿详情表数据开始******************/
                    $draft_detail['productId']              = $newProductId;
                    $draft_detail['aeopAeProductPropertys'] = $detail_info['aeopAeProductPropertys'];
                    $draft_detail['imageURLs']              = $detail_info['imageURLs'];
                    $detail                                 = htmlspecialchars_decode($detail_info['detail']);
                    $detail                                 = $smtApi->filterSmtRelationProduct($detail);//过滤关联产品
                    $draft_detail['detail']                 = htmlspecialchars($detail);                   
                    $draft_detail['productUnit']            = $detail_info['productUnit'];
                    $draft_detail['isImageDynamic']         = $detail_info['isImageDynamic'];
                    $draft_detail['isImageWatermark']       = $detail_info['isImageWatermark'];
                    $draft_detail['lotNum']                 = $detail_info['lotNum'];
                    $draft_detail['bulkOrder']              = $detail_info['bulkOrder'];
                    $draft_detail['packageType']            = $detail_info['packageType'];
                    $draft_detail['isPackSell']             = $detail_info['isPackSell'];
                    $draft_detail['bulkDiscount']           = $detail_info['bulkDiscount'];
                    $draft_detail['promiseTemplateId']      = $detail_info['promiseTemplateId'];
                    $draft_detail['src']                    = $detail_info['src'];
                    $draft_detail['freightTemplateId']      = $detail_info['freightTemplateId'];
                    $draft_detail['templateId']             = $detail_info['templateId'];
                    $draft_detail['shouhouId']              = $detail_info['shouhouId'];
                    $draft_detail['detail_title']           = $detail_info['detail_title'];
                    //  $draft_detail['sizechartId']            = $detail_info['sizechartId'];
                    $draft_detail['detailPicList']          = $detail_info['detailPicList'];
                    $detailLocal                            = htmlspecialchars_decode($detail_info['detailLocal']);
                    $detailLocal                            = $smtApi->filterSmtRelationProduct($detailLocal);//过滤关联产品
                    $draft_detail['detailLocal']            = htmlspecialchars($detailLocal);
        
                    unset($detail);
                    /***************插入到草稿详情表数据结束******************/
                    $detail_id = $this->Smt_product_detail_model->create($draft_detail);
                    if (!$detail_id) {
                        $error[] = $list_info['productId'] . ',tokenId:' . $token_id . '详情复制错误';
                        DB::rollback();
                        continue;
                    }
        
                    /***************插入到草稿SKU表数据开始******************/
                    $sku_flag = true;
                    if(!$sku_info){
                        $error[] = $list_info['productId'].'不存在SKU,复制失败';
                        continue;
                    }
                    foreach ($sku_info as $row) {
                        $draft_skus['productId']       = $newProductId;
                        $draft_skus['skuCode']         = $row['skuCode']; //这个需要处理下
                        $draft_skus['skuPrice']        = $row['skuPrice'];
                        $draft_skus['skuStock']        = $row['skuStock'];
                        $draft_skus['smtSkuCode']      = $smtApi->rebuildSmtSku($row['smtSkuCode']);
                        $draft_skus['skuMark']         = $draft_skus['productId'].':'.$row['skuCode'];
                        $draft_skus['aeopSKUProperty'] = $row['aeopSKUProperty']; //sku属性--注意可能含有图片
                        $draft_skus['ipmSkuStock']     = $row['ipmSkuStock'];
                        $draft_skus['overSeaValId']    = $row['overSeaValId'];
                        $sku_id                        = $this->Smt_product_skus_model->create($draft_skus);
        
                        if (!$sku_id) {
                            $sku_flag = false;
                            $error[] = $list_info['productId'] . ',tokenId:' . $token_id . 'SKU'.$draft_skus['skuCode'].'复制错误';
                            unset($draft_skus);
                            break;
                        }else {
                            unset($draft_skus);
                        }
                    }
                    /***************插入到草稿SKU表数据结束******************/
        
                    if ($sku_flag){
                        $flag = true;
                        DB::commit();
                    }else {
                        DB::rollback();
                    }
                }
            }
            unset($detail_info);
            unset($sku_info);
            unset($list_info);
        }
        
        $this->ajax_return('另存为草稿' . ($flag ? '成功' : '失败'), $flag, $error);
    }
    
    /**
     * 产品分组管理
     * @return [type] [description]
     */
    public function groupManage()
    {   
        $this->mainTitle = "产品分组";
        $this->mainIndex = route('smtProduct.groupManage');
        $token_id = request()->input('token_id');  
        $group = $this->Smt_product_group_model->getProductGroupList($token_id);
    
        //速卖通账号列表查询条件   
        $channel_id = ChannelModel::where('driver','aliexpress')->first()->id;
        $account_list = AccountModel::where(['channel_id'=>$channel_id,'is_available' => 1])->get();
        foreach($account_list as $account){
            $token_array[$account['id']] = $account->toArray();
        }
        
        
        if($token_id){
            $list =  $this->Smt_product_group_model->where('token_id',$token_id)->groupBy('token_id');
        }else{
            $list = $this->Smt_product_group_model->groupBy('token_id');
        }
        $response = array(
            'metas'    => $this->metas(__FUNCTION__),
            'data'     => $this->autoList($this->Smt_product_group_model,$list),
            'group'    => $group,
            'token'    => $token_array,
            'token_id' => $token_id
        );
        
        return view($this->viewPath.'group_list',$response);
    }
    
    /**
     * 服务模板列表管理
     */
    public function serviceManage(){
        $this->mainTitle = "SMT服务模版";
        $this->mainIndex = route('smtProduct.serviceManage');
        $token_id = request()->input('token_id');

        //速卖通账号列表查询条件
        $channel_id = ChannelModel::where('driver','aliexpress')->first()->id;
        $account_list = AccountModel::where(['channel_id'=>$channel_id,'is_available' => 1])->get();
        foreach($account_list as $account){
            $token_array[$account['id']] = $account->toArray();
        }
        $list = '';
        if($token_id){
            $list =  $this->Smt_service_template_model->where('token_id',$token_id);
        }
        $response = array(
            'metas'    => $this->metas(__FUNCTION__),
            'data'     => $this->autoList($this->Smt_service_template_model,$list),
            'token'    => $token_array,
            'token_id' => $token_id
        );
        return view($this->viewPath.'service_list',$response);
    }
    
    /**
     * 运费模版列表管理
     */
    public function freightManage(){
        $this->mainTitle = "SMT运费模版";
        $this->mainIndex = route('smtProduct.freightManage');        
        $token_id = request()->input('token_id');
        
        //速卖通账号列表查询条件
        $channel_id = ChannelModel::where('driver','aliexpress')->first()->id;
        $account_list = AccountModel::where(['channel_id'=>$channel_id,'is_available' => 1])->get();
        foreach($account_list as $account){
            $token_array[$account['id']] = $account->toArray();
        }
        $list = '';
        if($token_id){
            $list =  $this->Smt_freight_template_model->where('token_id',$token_id);
        }
        $response = array(
            'metas'    => $this->metas(__FUNCTION__),
            'data'     => $this->autoList($this->Smt_freight_template_model,$list),  
            'token'    => $token_array,
            'token_id' => $token_id
        );
        return view($this->viewPath.'freight_list',$response);
    }
    
    public function getFreightDetailById(){
        $this->mainTitle = "运费模版详情";
        $id = request()->input('id');
        $response = array(
            'metas'    => $this->metas(__FUNCTION__),
            'data'     => $this->autoList($this->Smt_freight_template_model->where('id',$id)),  
        );
        return view($this->viewPath.'show_freight_detail',$response);
    }
    
    
}
