<?php

use yii\db\Migration;

class m181108_074651_alter_user_table extends Migration
{

    public $tableName = "{{%user}}";

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'is_shop_admin', $this->smallInteger(1)->notNull()->defaultValue(0)->comment('是否是门店经营者'));
        $this->addColumn($this->tableName, '', $this->smallInteger(1)->notNull()->defaultValue(0)->comment('是否是门店经营者'));
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'is_shop_admin');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181108_074651_alter_user_table cannot be reverted.\n";

        return false;
    }
    */
}
