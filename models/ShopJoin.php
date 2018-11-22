<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%shop_join}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property string $community
 * @property string $name
 * @property string $mobile
 * @property integer $province
 * @property integer $city
 * @property integer $district
 * @property integer $status
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
            [['store_id', 'user_id', 'community', 'name', 'mobile'], 'required'],
            [['store_id', 'user_id', 'province', 'city', 'district', 'status', 'created_at'], 'integer'],
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
            'store_id' => 'Store ID',
            'user_id' => 'User ID',
            'community' => 'Community',
            'name' => 'Name',
            'mobile' => 'Mobile',
            'province' => 'Province',
            'city' => 'City',
            'district' => 'District',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }
}
