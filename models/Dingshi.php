<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%dingshi}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $start_time
 * @property string $end_time
 */
class Dingshi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dingshi}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id'], 'required'],
            [['store_id'], 'integer'],
            [['start_time', 'end_time'], 'string', 'max' => 32],
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
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
        ];
    }
}
