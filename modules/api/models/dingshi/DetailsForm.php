<?php
/**
 * Created by PhpStorm.
 * User: peize
 * Date: 2018/3/12
 * Time: 10:31
 */

namespace app\modules\api\models\dingshi;


use app\models\Dingshi;
use app\models\DingshiGoods;
use app\models\Goods;
use app\models\GoodsPic;
use app\utils\GetInfo;
use app\modules\api\models\ApiModel;

class DetailsForm extends ApiModel
{
    public $id;
    public $user_id;
    public $store_id;
    public $dingshi_goods;
    public $scene_type;  //1--表示扫描商品海报小程序码进入
    public $goods_id;

    public function rules()
    {
        return [
            [['user_id'], 'safe'],
            [['scene_type', 'id', 'goods_id'], 'integer']
        ];
    }

    /**
     * @return array
     * 秒杀商品详情
     */
    public function search($return = false)
    {
        if (!$this->validate())
            return $this->errorResponse;
        /*
        if ($this->id) {
            $this->dingshi_goods = DingshiGoods::findOne(['id' => $this->id]);
            if (!$this->dingshi_goods) {
                return [
                    'code' => 1,
                    'msg' => '商品不存在或已下架',
                ];
            }
            if ($this->scene_type == 1) {
                if ($this->dingshi_goods->open_date != date('Y-m-d')) {
                    $this->dingshi_goods = DingshiGoods::find()->where([
                        'goods_id' => $this->dingshi_goods->goods_id,
                        'is_delete' => 0,
                        'store_id' => $this->dingshi_goods->store_id
                    ])->andWhere(['or', ['open_date' => date('Y-m-d')], ['>', 'open_date', date('Y-m-d')]])
                        ->orderBy(['open_date' => SORT_ASC, 'start_time' => SORT_ASC])->one();

                    if (!$this->dingshi_goods) {
                        return [
                            'code' => 1,
                            'msg' => '商品不存在或已下架',
                        ];
                    }
                }
            }
        }
        */
        if($this->goods_id){
            $this->dingshi_goods = DingshiGoods::find()->where(['goods_id' => $this->goods_id, 'store_id'=> $this->store_id])
                ->andWhere(['open_date' => date('Y-m-d')])->one();
            if (!$this->dingshi_goods) {
                return [
                    'code' => 1,
                    'msg' => '商品暂无秒杀活动',
                ];
            }
        }
        $goods = Goods::findOne([
            'id' => $this->dingshi_goods->goods_id,
            'is_delete' => 0,
            'status' => 1,
            'store_id' => $this->store_id,
        ]);
        if (!$goods)
            return [
                'code' => 1,
                'msg' => '商品不存在或已下架',
            ];
        $pic_list = GoodsPic::find()->select('pic_url')->where(['goods_id' => $goods->id, 'is_delete' => 0])->asArray()->all();
        $is_favorite = 0;

        $service_list = explode(',', $goods->service);
        $new_service_list = [];
        if (is_array($service_list))
            foreach ($service_list as $item) {
                $item = trim($item);
                if ($item)
                    $new_service_list[] = $item;
            }
        $res_url = GetInfo::getVideoInfo($goods->video_url);
        $goods->video_url = $res_url['url'];
        $dingshi = $this->getdingshiData($goods->id);
        $dingshi_data = $dingshi['dingshi_data'];
        if ($dingshi_data) {
            $dingshi_data['dingshi_price'] = number_format($dingshi_data['dingshi_price'], 2, '.', '');
            $dingshi_data['rest_num'] = min((int)$goods->getNum(), (int)$dingshi_data['dingshi_num']) - $dingshi_data['sell_num'];
        }
        $dingshi['dingshi_data'] = $dingshi_data;

        $old = [];
        $new = [];

        foreach (json_decode($this->dingshi_goods->attr) as $v) {
            if ($v->price > 0) {
                $old[] = (float)$v->price;
            } else {
                $old[] = (float)$goods->original_price;
            }
            if ($v->dingshi_price > 0) {
                $new[] = (float)$v->dingshi_price;
            } else {
                if ($v->price > 0) {
                    $new[] = (float)$v->price;
                } else {
                    $new[] = (float)$goods->original_price;
                }

            }
        };
        $dingshi['old_small_price'] = min($old);
        $dingshi['old_big_price'] = max($old);
        $dingshi['new_small_price'] = min($new);
        $dingshi['new_big_price'] = max($new);

        if ($return) {
            return [
                'id' => $goods->id,
                'attr' => $goods->attr,
                'pic_list' => $pic_list,
                'cover_pic' => $goods->cover_pic,
                'attr_pic' => $pic_list[0]['pic_url'],
                'name' => $goods->name,
                'price' => floatval($goods->original_price),
                'detail' => $goods->detail,
                'sales_volume' => $goods->getSalesVolume() + $goods->virtual_sales,
                'attr_group_list' => $goods->getAttrGroupList(),
                'num' => $goods->getNum(),
                'is_favorite' => $is_favorite,
                'service_list' => $new_service_list,
                'original_price' => floatval($goods->original_price),
                'video_url' => $goods->video_url,
                'unit' => $goods->unit,
                'dingshi' => $dingshi,
                'use_attr' => intval($goods->use_attr),
            ];
        }

        return [
            'code' => 0,
            'data' => (object)[
                'id' => $goods->id,
                'attr' => $goods->attr,
                'pic_list' => $pic_list,
                'cover_pic' => $goods->cover_pic,
                'attr_pic' => $pic_list[0]['pic_url'],
                'name' => $goods->name,
                'price' => floatval($goods->original_price),
                'detail' => $goods->detail,
                'sales_volume' => $goods->getSalesVolume() + $goods->virtual_sales,
                'attr_group_list' => $goods->getAttrGroupList(),
                'num' => $goods->getNum(),
                'is_favorite' => $is_favorite,
                'service_list' => $new_service_list,
                'original_price' => floatval($goods->original_price),
                'video_url' => $goods->video_url,
                'unit' => $goods->unit,
                'dingshi' => $dingshi,
                'use_attr' => intval($goods->use_attr),
            ],
        ];
    }

    //获取商品秒杀数据
    public function getdingshiData($goods_id)
    {
        $dingshi_goods = $this->dingshi_goods;
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
        $dingshi_data = null;
        if (count($attr_data) == 1) {
            $dingshi_data = $attr_data[0];
        }

        $dingshi = Dingshi::find()->where(['store_id' => $this->store_id])->one();

        return [
            'dingshi_num' => $total_dingshi_num,
            'sell_num' => $total_sell_num,
            'dingshi_price' => (float)$dingshi_price,
            'begin_time' => strtotime($dingshi_goods->open_date . ' ' . $dingshi->start_time . ':00:00'),
            'end_time' => strtotime($dingshi_goods->open_date . ' ' . $dingshi->end_time. ':00:00'),
            'now_time' => time(),
            'dingshi_data' => $dingshi_data,
            'dingshi_goods_id' => $this->dingshi_goods->id
        ];
    }


}
