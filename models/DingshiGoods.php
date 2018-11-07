<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%dingshi_goods}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $goods_id
 * @property string $open_date
 * @property string $attr
 * @property integer $created_at
 * @property integer $updated_at
 * @property Goods $goods
 */
class DingshiGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dingshi_goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'goods_id', 'attr'], 'required'],
            [['store_id', 'goods_id', 'created_at', 'updated_at'], 'integer'],
            [['open_date'], 'safe'],
            [['attr'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'goods_id' => 'Goods ID',
            'open_date' => 'Open Date',
            'attr' => 'Attr',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }
}
