<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shop_join`.
 */
class m181121_071331_create_shop_join_table extends Migration
{
    public $tableName = '{{%shop_join}}';
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'store_id' => $this->integer(11)->notNull()->comment('门店id'),
            'user_id' => $this->integer(11)->notNull()->comment('用户id'),
            'community' => $this->string(255)->notNull()->comment('社区名称'),
            'name' => $this->string(255)->notNull()->comment('姓名'),
            'mobile' => $this->string(255)->notNull()->comment('手机号'),
            'province' => $this->smallInteger(1)->notNull()->defaultValue(0)->comment('省份'),
            'city' => $this->smallInteger(1)->notNull()->defaultValue(0)->comment('省份'),
            'district' => $this->smallInteger(1)->notNull()->defaultValue(0)->comment('省份'),
            'status' => $this->smallInteger(1)->notNull()->defaultValue(0)->comment('状态'),
            'created_at' => $this->integer(11)->notNull()->defaultValue(0)->comment('申请时间')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
