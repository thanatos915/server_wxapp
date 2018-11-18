<?php

use yii\db\Migration;

class m181118_101903_alter_cart extends Migration
{

    public $tableName = '{{%cart}}';

    public function safeUp()
    {

        $this->addColumn($this->tableName, 'source',$this->smallInteger(1)->notNull()->defaultValue(0)->comment('商品来源'));

    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'source');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181118_101903_alter_cart cannot be reverted.\n";

        return false;
    }
    */
}
