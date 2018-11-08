<?php

use yii\db\Migration;

class m181108_065559_alter_shop_table extends Migration
{
    public $tableName = "{{%shop}}";

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'user_id', $this->integer(11)->notNull()->unsigned()->comment('用户id')->after('store_id'));
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'user_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181108_065559_alter_shop_table cannot be reverted.\n";

        return false;
    }
    */
}
