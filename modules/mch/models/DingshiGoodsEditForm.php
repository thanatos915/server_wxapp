<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/11
 * Time: 20:26
 */

namespace app\modules\mch\models;

use app\models\DingshiGoods;
use app\models\Goods;

class DingshiGoodsEditForm extends MchModel
{
    public $id;
    public $goods_id;
    public $store_id;
    public $attr;
    public $open_date;

    public function rules()
    {
        return [
            [['goods_id', 'attr' , 'open_date'], 'required'],
            [['goods_id', 'id'], 'integer'],
            ['price', 'safe'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $exist = Goods::find()->where(['id' => $this->goods_id, 'store_id' => $this->store_id])->exists();
        if (!$exist) {
            return [
                'code' => 0,
                'msg' => '该商品不存在，请选择其它商品',
            ];
        }

        $open_date = json_decode($this->open_date, true);
        foreach ($open_date as $date) {
                $model = DingshiGoods::findOne([
                    'goods_id' => $this->goods_id,
                    'open_date' => $date,
                ]);
                \Yii::trace("---->" . ($model == null));
                if (!$model) {
                    $model = new DingshiGoods();
                    $model->store_id = $this->store_id;
                    $model->goods_id = $this->goods_id;
                    $model->open_date = $date;
                }
                $model->attr = $this->attr;
                $model->save();
        }

        return [
            'code' => 0,
            'msg' => '保存成功',
            'data' => [
                'return_url' => \Yii::$app->urlManager->createUrl(['mch/dingshi/goods-detail', 'goods_id' => $this->goods_id]),
            ],
        ];
    }
}
