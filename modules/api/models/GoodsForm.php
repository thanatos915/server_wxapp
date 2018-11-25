<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/15
 * Time: 9:56
 */

namespace app\modules\api\models;

use app\models\Dingshi;
use app\models\DingshiGoods;
use app\utils\GetInfo;
use app\hejiang\ApiResponse;
use app\models\Favorite;
use app\models\Goods;
use app\models\GoodsPic;
use app\models\Mch;
use app\models\MiaoshaGoods;
use app\modules\api\models\mch\ShopDataForm;

class GoodsForm extends ApiModel
{
    public $id;
    public $user_id;
    public $store_id;

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['user_id'], 'safe'],
        ];
    }

    /**
     * 排序类型$sort   1--综合排序 2--销量排序
     */
    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $goods = Goods::findOne([
            'id' => $this->id,
            'is_delete' => 0,
            'status' => 1,
            'store_id' => $this->store_id,
            'type' => get_plugin_type()
        ]);
        if (!$goods) {
            return new ApiResponse(1, '商品不存在或已下架');
        }
        $mch = null;
        if ($goods->mch_id) {
            $mch = $this->getMch($goods);
            if (!$mch) {
                return new ApiResponse(1, '店铺已经打烊了哦~');
            }
        }
        $pic_list = GoodsPic::find()->select('pic_url')->where(['goods_id' => $goods->id, 'is_delete' => 0])->asArray()->all();
        $is_favorite = 0;
        if ($this->user_id) {
            $exist_favorite = Favorite::find()->where(['user_id' => $this->user_id, 'goods_id' => $goods->id, 'is_delete' => 0])->exists();
            if ($exist_favorite) {
                $is_favorite = 1;
            }
        }
        $service_list = explode(',', $goods->service);
        $new_service_list = [];
        if (is_array($service_list)) {
            foreach ($service_list as $item) {
                $item = trim($item);
                if ($item) {
                    $new_service_list[] = $item;
                }
            }
        }
        $price = [];
        foreach(json_decode($goods->attr) as $v){
            if($v->price>0){
                $price[] = $v->price;
            }else{
                $price[] = floatval($goods->price);
            }
        }

        $res_url = GetInfo::getVideoInfo($goods->video_url);
        $goods->video_url = $res_url['url'];

        if($goods->is_negotiable) {
            $min_price = Goods::GOODS_NEGOTIABLE;
        }else{
            $min_price = min($price);
        }

        $data = [
            'id' => $goods->id,
            'pic_list' => $pic_list,
            'attr'=>$goods->attr,
            'is_negotiable'=>$goods->is_negotiable,
            'max_price'=>max($price),
            'min_price'=> $min_price,
            'name' => $goods->name,
            'cat_id' => $goods->cat_id,
            'price' => floatval($goods->price),
            'detail' => $goods->detail,
            'sales_volume' => $goods->getSalesVolume() + $goods->virtual_sales,
            'attr_group_list' => $goods->getAttrGroupList(),
            'num' => $goods->getNum(),
            'is_favorite' => $is_favorite,
            'service_list' => $new_service_list,
            'original_price' => floatval($goods->original_price),
            'video_url' => $goods->video_url,
            'unit' => $goods->unit,
            'dingshi' => $this->getMiaoshaData($goods->id),
            'use_attr' => intval($goods->use_attr),
            'mch' => $mch,
        ];
        return new ApiResponse(0, 'success', $data);
    }

    //获取商品秒杀数据
    public function getMiaoshaData($goods_id)
    {
        $dingshi_goods = DingshiGoods::findOne([
            'goods_id' => $goods_id,
            'open_date' => date('Y-m-d'),
        ]);
        if (!$dingshi_goods) {
            return null;
        }
        $attr_data = json_decode($dingshi_goods->attr, true);
        $total_dingshi_num = 0;
        $total_sell_num = 0;
        $dingshi_price = 0.00;
        foreach ($attr_data as $i => $attr_data_item) {
            $total_dingshi_num += $attr_data_item['dingshi_num'];
            $total_sell_num += $attr_data_item['sell_num'];
            if ($dingshi_price == 0) {
                $dingshi_price = $attr_data_item['dingshi_price'];
            } else {
                $dingshi_price = min($dingshi_price, $attr_data_item['dingshi_price']);
            }
        }

        $dingshi = Dingshi::find()->where(['store_id' => $this->store_id])->one();

        return [
            'dingshi_num' => $total_dingshi_num,
            'sell_num' => $total_sell_num,
            'dingshi_price' => (float)$dingshi_price,
            'begin_time' => strtotime($dingshi_goods->open_date . ' ' . $dingshi->start_time. ':00'),
            'end_time' => strtotime($dingshi_goods->open_date . ' ' . $dingshi->end_time. ':00'),
            'now_time' => time(),
        ];
    }


    // 快速给购买商品
    public function quickGoods($twocatid)
    {
        $goods = Goods::find()
            ->where([
                'store_id' => $this->store_id,
                'is_delete' => 0,
                'status' => 1,
                'quick_purchase' => 1
            ])
            ->andWhere([

                'in', 'cat_id', $twocatid
            ])->asArray()
            ->all();
        foreach ($goods as $key => &$value) {
            $value['attr'] = json_decode($value['attr']);
            foreach ($value['attr'] as $key2 => $value2) {
                foreach ($value2->attr_list as $key3 => $value3) {
                    $value['attr_name'] = $value3->attr_name;
                }
                // $value['attr_num'][] = $value2->num;
                // $value['attr_price'][] = $value2->price;
                // $value['attr_no'][] = $value2->no;
                // $value['attr_pic'][] = $value2->pic;
                $value['num'] = 0;
            }
            // unset($value['attr']);
        }
        return [
            'code' => 0,
            'data' => [
                'list' => $goods,
            ],
        ];
    }

    /**
     * @param Goods $goods
     */
    public function getMch($goods)
    {
        $f = new ShopDataForm();
        $f->mch_id = $goods->mch_id;
        $shop = $f->getShop();
        if (isset($shop['code']) && $shop['code'] == 1) {
            return null;
        }
        return $shop;
    }
}
