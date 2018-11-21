<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%shop_join}}".
 *
 * @property integer $id
 * @property string $community
 * @property string $name
 * @property string $mobile
 * @property integer $province
 * @property integer $city
 * @property integer $district
 * @property integer $created_at
 */
class ShopJoin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_join}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['community', 'name', 'mobile'], 'required'],
            [['province', 'city', 'district', 'created_at'], 'integer'],
            [['community', 'name', 'mobile'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'community' => 'Community',
            'name' => 'Name',
            'mobile' => 'Mobile',
            'province' => 'Province',
            'city' => 'City',
            'district' => 'District',
            'created_at' => 'Created At',
        ];
    }
}
