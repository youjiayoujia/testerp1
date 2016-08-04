<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-06-08
 * Time: 14:55
 */

namespace App\Http\Controllers\Publish\Wish;

use Channel;
use App\Models\Channel\AccountModel;
use App\Http\Controllers\Controller;
use App\Models\ChannelModel;
use App\Models\Publish\Wish\WishPublishProductModel;
use App\Models\Publish\Wish\WishPublishProductDetailModel;
use App\Models\Publish\Wish\WishSellerCodeModel;

class WishPublishController extends Controller
{
    public function __construct(WishPublishProductModel $wishProduct, WishPublishProductDetailModel $wishProductDetail,WishSellerCodeModel $sellerCode)
    {
        $this->model = $wishProduct;
        $this->mainIndex = route('wish.index');
        $this->mainTitle = 'wish刊登';
        $this->viewPath = 'publish.wish.';
        $this->wishProduct = $wishProduct;
        $this->wishProductDetail = $wishProductDetail;
        $this->sellerCode = $sellerCode;
        $this->channel_id = ChannelModel::where('driver', 'wish')->first()->id;
    }

    public function index()
    {
        request()->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model->where('product_type_status', '!=', 2)),

        ];
        return view($this->viewPath . 'index', $response);
    }

    public function indexOnlineProduct()
    {
        request()->flash();
        $this->mainTitle = 'wish在线数据';
        $this->mainIndex = route('wish.indexOnlineProduct');
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model->where('product_type_status', 2)),
            'mixedSearchFields' => $this->model->mixed_search,

        ];
        $response['mixedSearchFields']
        ['filterSelects'] = [
            'account_id' => $this->wishProduct->getChannelAccount(3),
            'sellerID' =>$this->sellerCode->getWishCodeWithName(),

        ];
        return view($this->viewPath . 'index', $response);
    }


    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'account' => AccountModel::where('channel_id', $this->channel_id)->get()->lists('account', 'id'),
        ];
        return view($this->viewPath . 'create', $response);
    }


    public function edit($id)
    {

        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'account' => AccountModel::where('channel_id', $this->channel_id)->get()->lists('account', 'id'),
        ];
        return view($this->viewPath . 'create', $response);
    }


    public function editOnlineProduct()
    {
        $this->mainTitle = 'wish在线数据修改';
        $this->mainIndex = route('wish.indexOnlineProduct');
        $id = $_GET['id'];
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }

        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'account' => AccountModel::where('channel_id', $this->channel_id)->get()->lists('account', 'id'),
        ];
        return view($this->viewPath . 'editOnlineProduct', $response);
    }


    public function store()
    {

        $post = $_POST;
        if ($post['action'] == 'save') {
            $this->save($post);
        }

        if ($post['action'] == 'editAndPost') {
            $id = $this->save($post, true);
            $this->editAndPost($id);
        }


    }

    //保存为草稿
    public function save($post, $exit = false)
    {

        $accountInfo = AccountModel::where('channel_id', $this->channel_id)->get()->lists('account', 'id');
        $result = [];
        foreach ($post['choose_account'] as $k => $account) {
            $channel_accounts = AccountModel::where('id', $account)->first();
            $is_add = true;
            $wish_product = array();
            $wish_product_detail = array();
            $wish_product['account_id'] = intval($account);
            $wish_product['publishedTime'] = date('Y-m-d H:i:s');
            $wish_product['sellerID'] = '';
            $description = $post['content'];
            $description = str_replace('<p>', ' ', $description);
            $description = str_replace('</p>', '\n ', $description);
            $descripts = str_replace('&nbsp;', ' ', $description);
            $wish_product['product_description'] = strip_tags($descripts);;


            $wish_product['product_name'] = $post['account_tittle'][$account]['tittle'];
            $wish_product['parent_sku'] = $this->autoFillSuffix(trim($post['parent_sku']), $channel_accounts->domain, $channel_accounts->wish_sku_resolve);
            $wish_product['tags'] = $post['account_tags'][$account]['tags'];

            $wish_product['brand'] = $post['brand'];
            $wish_product['landing_page_url'] = $post['landing_page_url'];
            $wish_product['upc'] = $post['upc'];


            if (empty($channel_accounts->image_domain)) {
                $result[$k]['id'] = '';
                $result[$k]['account'] = $accountInfo[intval($account)];
                $result[$k]['info'] = '未找到对应图片域名';
                continue;
            } else {//replaceDomainPictures
                if (!empty($post["extra_images"])) {
                    foreach ($post["extra_images"] as $key => $image) {
                        $post["extra_images"][$key] = $this->replaceDomainPictures($image, $channel_accounts->image_domain);
                    }
                }


            }


            $wish_product['extra_images'] = implode('|', $post["extra_images"]);

            foreach ($post['arr']['sku'] as $key => $variant) {

                //autoFillSuffix
                $wish_product_detail[$key]['sku'] = $this->autoFillSuffix(trim($variant), $channel_accounts->domain, $channel_accounts->wish_sku_resolve);

                $wish_product_detail[$key]['account_id'] = intval($account);
                $wish_product_detail[$key]['price'] = !empty($post['account_price'][$account][$key]) ? $post['account_price'][$account][$key] : $post['arr']['price'][$key];
                $wish_product_detail[$key]['inventory'] = isset($post['arr']['quantity'][$key]) ? $post['arr']['quantity'][$key] : "9999";
                $wish_product_detail[$key]['color'] = isset($post['arr']['color'][$key]) ? $post['arr']['color'][$key] : '';
                $wish_product_detail[$key]['size'] = isset($post['arr']['size'][$key]) ? $post['arr']['size'][$key] : '';
                $wish_product_detail[$key]['shipping'] = $post['account_shpping'][$account]['shipping'];
                $wish_product_detail[$key]['msrp'] = $post['msrp'];
                $wish_product_detail[$key]['shipping_time'] = $post['shipping_time'];
                $wish_product_detail[$key]['main_image'] = isset($post['arr']['main_image'][$key]) ? $post['arr']['main_image'][$key] : '';
                if (!empty($wish_product_detail[$key]['main_image'])) {
                    $wish_product_detail[$key]['main_image'] = $this->replaceDomainPictures($wish_product_detail[$key]['main_image'], $channel_accounts->image_domain);
                }
            }


            if (!empty($post['id'])) {
                $ids = explode(',', $post['id']);
                foreach ($ids as $v) {
                    if (!empty($v)) {
                        $thisProduct = $this->wishProduct->where('id', $v)->where('account_id', $account)->first();

                        if ($thisProduct) {
                            $is_add = false;
                            $mark_id = $v;
                            break;
                        }
                    }
                }
            }
            if ($is_add) {
                $wish_product['product_type_status'] = 1;
                $wish = $this->wishProduct->create($wish_product);
                $result[$k]['id'] = $wish->id;
                $result[$k]['account'] = $accountInfo[intval($account)];
                $result[$k]['info'] = '新增成功';
                foreach ($wish_product_detail as $detail) {
                    $detail['product_id'] = $wish->id;
                    $wishDetail = $this->wishProductDetail->create($detail);
                }

            } else {
                //  $wish_product['id']=$mark_id;
                $this->wishProduct->where('id', $mark_id)->update($wish_product);
                foreach ($wish_product_detail as $key1 => $item) {

                    $productDetail = $this->wishProduct->find($mark_id)->details;
                    if (count($wish_product_detail) == count($productDetail)) {
                        foreach ($productDetail as $key2 => $productItem) {
                            if ($key1 == $key2) {
                                $productItem->update($item);
                            }
                        }
                    } else {
                        foreach ($productDetail as $key2 => $orderItem) {
                            $orderItem->delete($item);
                        }
                        foreach ($wish_product_detail as $value) {
                            $value['product_id'] = $mark_id;
                            $this->wishProductDetail->create($value);
                        }
                    }
                }
                $result[$k]['id'] = $mark_id;
                $result[$k]['account'] = $accountInfo[intval($account)];
                $result[$k]['info'] = '修改成功';
            }

        }

        $string = '';
        $id = [];

        foreach ($result as $re) {
            $string = $string . '<br/>' . $re['account'] . ' :' . $re['info'];
            $id[] = $re['id'];
        }

        if ($exit) {
            return $result;
        } else {
            $this->ajax_return($string, 1, implode(',', $id));
        }

    }


    //发布
    public function editAndPost($data)
    {
        foreach ($data as $data_key => $v) {
            $productInfo = $this->wishProduct->where('id', $v['id'])->first();
            $productInfoDetail = $this->wishProduct->find($v['id'])->details;
            if (empty($productInfo->productID)) {
                $needPublishSku = 0;
                $hasPublishSku = 0;
                foreach ($productInfoDetail as $key => $detail) {
                    $needPublishSku++;
                    $account = AccountModel::findOrFail($productInfo->account_id);
                    $wishApi = Channel::driver($account->channel->driver, $account->api_config);
                    if ($key == 0) {
                        $addInfo = array();
                        $addInfo['name'] = trim($productInfo->product_name);
                        $addInfo['description'] = $productInfo->product_description;
                        $addInfo['tags'] = trim($productInfo->tags);
                        $addInfo['sku'] = $detail->sku;
                        $addInfo['color'] = $detail->color;
                        $addInfo['size'] = $detail->size;
                        $addInfo['inventory'] = $detail->inventory;
                        $addInfo['price'] = $detail->price;
                        $addInfo['shipping'] = $detail->shipping;
                        $addInfo['msrp'] = $detail->msrp;
                        $addInfo['shipping_time'] = $detail->shipping_time;
                        $addInfo['main_image'] = $detail->main_image;
                        if (empty($addInfo['main_image'])) {
                            $main_image = explode('|', $productInfo->extra_images);
                            $addInfo['main_image'] = $main_image[0];
                        }
                        $addInfo['parent_sku'] = $productInfo->parent_sku;
                        $addInfo['brand'] = $productInfo->brand;
                        $addInfo['landing_page_url'] = $productInfo->landing_page_url;
                        $addInfo['upc'] = $productInfo->upc;
                        $addInfo['extra_images'] = $productInfo->extra_images;
                        foreach ($addInfo as $k => $info) {
                            if (empty($info))
                                unset($addInfo[$k]);
                        }

                        $productResult = $wishApi->createProduct($addInfo);
                        if (!$productResult['status']) {
                            $data[$data_key]['info'] = $productResult['info'];
                            break;
                        }
                        $productID = $productResult['info'];
                        $updateInfo = array();
                        $updateInfo['productID'] = $productID;
                        $updateInfo['publishedTime'] = date('Y-m-d H:i:s');
                        $updateInfo['is_promoted'] = 0;
                        $updateInfo['review_status'] = 'pending';
                        $updateInfo['number_saves'] = 0;
                        $updateInfo['number_sold'] = 0;
                        $updateInfo['product_type_status'] = 2;
                        $updateInfo['status'] = 1;
                        $this->wishProduct->where('id', $v['id'])->update($updateInfo);


                        $updateInfoDetail = array();
                        $updateInfoDetail['productID'] = $productID;
                        $updateInfoDetail['enabled'] = 1;
                        $this->wishProductDetail->where('id', $detail->id)->update($updateInfoDetail);
                        $data[$data_key]['info'] = '上架成功 productID: ' . $productID;
                        $hasPublishSku++;

                        //更新product表的信息
                    } else {
                        $variant = array();
                        $variant['parent_sku'] = $productInfo->parent_sku;
                        $variant['sku'] = $detail->sku;
                        $variant['color'] = $detail->color;
                        $variant['size'] = $detail->size;
                        $variant['inventory'] = $detail->inventory;
                        $variant['price'] = $detail->price;
                        $variant['shipping'] = $detail->shipping;
                        $variant['msrp'] = $detail->msrp;
                        $variant['shipping_time'] = $detail->shipping_time;
                        $variant['main_image'] = $detail->main_image;
                        foreach ($variant as $k => $info) {
                            if (empty($info))
                                unset($variant[$k]);
                        }
                        $result = $wishApi->createVariation($variant);
                        if ($result) { //更新对应表的信息
                            $hasPublishSku++;
                            $updateInfoDetail = array();
                            $updateInfoDetail['productID'] = $productID;
                            $updateInfoDetail['enabled'] = 1;
                            $this->wishProductDetail->where('id', $detail->id)->update($updateInfoDetail);

                        }
                    }
                    if ($key == (count($productInfoDetail) - 1))
                        $data[$data_key]['info'] = $data[$data_key]['info'] . ' ' . $hasPublishSku . '/' . $needPublishSku;
                }
            } else {
                $data[$data_key]['info'] = '已经上架了';
            }
        }
        $string = '';
        $id = [];

        foreach ($data as $re) {
            $string = $string . '<br/>' . $re['account'] . ' :' . $re['info'];
            $id[] = $re['id'];
        }

        $this->ajax_return($string, 1, implode(',', $id));

    }

    public function editOnlineProductStore()
    {
        $post = $_POST;
        $product = [];
        $productInfo = $this->wishProduct->where('id', $post['id'])->first();
        $account_id = $productInfo->account_id;

        $product['name'] = $post['account_tittle'][$account_id]['tittle'];
        $description = $post['content'];
        $description = str_replace('<p>', ' ', $description);
        $description = str_replace('</p>', '\n ', $description);
        $descripts = str_replace('&nbsp;', ' ', $description);
        $product['description'] = $descripts;
        $product['tags'] = $post['account_tags'][$account_id]['tags'];
        $product['brand'] = $post['brand'];
        $product['landing_page_url'] = $post['landing_page_url'];
        $product['upc'] = $post['upc'];
        //  $product['main_image'] =$post['name'];
        // $product['extra_images'] =implode('|',$post["extra_images"]);
        $product['id'] = $productInfo->productID;
        foreach ($product as $k => $info) {
            if (empty($info))
                unset($product[$k]);
        }
        //updateProduct
        $account = AccountModel::findOrFail($account_id);
        $wishApi = Channel::driver($account->channel->driver, $account->api_config);
        $result = $wishApi->updateProduct($product);
        if ($result['status']) {
            $erp_data = [];
            $erp_data['product_name'] = isset($product['name']) ? $product['name'] : '';
            $erp_data['tags'] = isset($product['tags']) ? $product['tags'] : '';
            $erp_data['brand'] = isset($product['brand']) ? $product['brand'] : '';
            $erp_data['landing_page_url'] = isset($product['landing_page_url']) ? $product['landing_page_url'] : '';
            $erp_data['upc'] = isset($product['upc']) ? $product['upc'] : '';
            $erp_data['product_description'] = isset($product['description']) ? $product['description'] : '';;
            //  $erp_data['extra_images']= isset($product['extra_images'])?$product['extra_images']:'';
            foreach ($erp_data as $k => $info) {
                if (empty($info))
                    unset($erp_data[$k]);
            }
            $this->wishProduct->where('id', $post['id'])->update($erp_data);

            $this->ajax_return($result['info']);
        } else {
            $this->ajax_return($result['info']);
        }


    }


    public function ajaxEditOnlineProduct()
    {
        $get = $_GET;
        $variant = [];
        $account = AccountModel::findOrFail($get['account_id']);
        $wishApi = Channel::driver($account->channel->driver, $account->api_config);
        if ($get['type'] == 1) {//上架 变成启用
            $variant['sku'] = $get['sku'];
            $url = 'https://china-merchant.wish.com/api/v2/variant/enable';


        } elseif ($get['type'] == 2) {//下架
            $variant['sku'] = $get['sku'];
            $url = 'https://china-merchant.wish.com/api/v2/variant/disable';
        } elseif ($get['type'] == 3) { //更新
            $variant['sku'] = $get['sku'];
            $variant['color'] = $get['color'];
            $variant['size'] = $get['size'];
            $variant['inventory'] = $get['inventory'];
            $variant['price'] = $get['price'];
            $variant['shipping'] = $get['shipping'];
            $variant['msrp'] = $get['msrp'];
            $variant['shipping_time'] = $get['shipping_time'];
            $variant['main_image'] = $get['main_image'];
            $url = 'https://china-merchant.wish.com/api/v2/variant/update';
        } elseif ($get['type'] == 4) { // 加新变量
            $variant['parent_sku'] = $get['parent_sku'];
            $variant['sku'] = $get['sku'];
            $variant['color'] = $get['color'];
            $variant['size'] = $get['size'];
            $variant['inventory'] = $get['inventory'];
            $variant['price'] = $get['price'];
            $variant['shipping'] = $get['shipping'];
            $variant['msrp'] = $get['msrp'];
            $variant['shipping_time'] = $get['shipping_time'];
            $variant['main_image'] = $get['main_image'];
            $url = 'https://china-merchant.wish.com/api/v2/variant/add';


        }
        $result = $wishApi->updateProductVariation($variant, $url);
        if ($result['status']) {
            if ($get['type'] == 1) {
                $updata['enabled'] = 1;
                $this->wishProductDetail->where('product_id', $get['id'])->where('sku', $get['sku'])->update($updata);

            } elseif ($get['type'] == 2) {
                $updata['enabled'] = 0;
                $this->wishProductDetail->where('product_id', $get['id'])->where('sku', $get['sku'])->update($updata);


            } elseif ($get['type'] == 3) {
                $this->wishProductDetail->where('product_id', $get['id'])->where('sku', $get['sku'])->update($variant);
            } elseif ($get['type'] == 4) {

                $productID = $this->wishProduct->find($get['id'])->first()->productID;
                $variant['productID'] = $productID;
                $variant['product_id'] = $get['id'];
                $variant['enabled'] = 1;
                $variant['account_id'] = $get['account_id'];
                $this->wishProductDetail->create($variant);
            }

            $this->ajax_return($result['info'], 1, $get['type']);

        } else {
            $this->ajax_return($result['info'], 2);
        }
    }


    function ajaxOperateOnlineProduct()
    {
        $get = $_GET;

        $product = [];
        $wishProduct = $this->wishProduct->find($get['id'])->first();
        $account = AccountModel::findOrFail($wishProduct->account_id);
        $wishApi = Channel::driver($account->channel->driver, $account->api_config);
        $url = 'https://china-merchant.wish.com/api/v2/product/' . $get['type'];
        $product['id'] = $wishProduct->productID;
        $result = $wishApi->updateProductVariation($product, $url);
        if ($result['status']) {
            $updateInfo = [];
            $updateDetailInfo = [];
            if ($get['type'] == 'enable') {
                $updateInfo['status'] = 1;
                $updateDetailInfo['enabled'] = 1;
                $this->wishProduct->where('productID', $wishProduct->productID)->update($updateInfo);
                $this->wishProductDetail->where('productID', $wishProduct->productID)->update($updateDetailInfo);

            }

            if ($get['type'] == 'disable') {
                $updateInfo['status'] = 0;
                $updateDetailInfo['enabled'] = 0;
                $this->wishProduct->where('productID', $wishProduct->productID)->update($updateInfo);
                $this->wishProductDetail->where('productID', $wishProduct->productID)->update($updateDetailInfo);
            }

            $this->ajax_return($result['info'], 1);
        } else {
            $this->ajax_return($result['info'], 2);
        }

    }

    function autoFillSuffix($sku, $suffix, $type)
    {
        $retrunSku = [];
        if (stripos($sku, '[') !== false) { //先去除【】
            $sku = preg_replace('/\[.*\]/', '', $sku);
        }
        $sku = explode('+', $sku);
        foreach ($sku as $v) {
            if ($type == 2) { //特殊处理
                $retrunSku[] = $v;
            } else {
                $retrunSku[] = $v . $suffix;
            }
        }

        return implode('+', $retrunSku);
    }

    function replaceDomainPictures($picture, $domain)
    {
        $picture = str_replace('imgurl.moonarstore.com', $domain, $picture);
        $picture = str_replace('getSkuImageInfo-resize', 'getSkuImageInfo', $picture);
        return $picture;
    }

    function ajax_return($info = '', $status = 1, $data = '')
    {
        $result = array('data' => $data, 'info' => $info, 'status' => $status);
        exit(json_encode($result));
    }
}
