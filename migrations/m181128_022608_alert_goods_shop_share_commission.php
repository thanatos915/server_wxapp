<?php

use yii\db\Migration;

class m181128_022608_alert_goods_shop_share_commission extends Migration
{
    public $tableName = '{{%goods}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'shop_share_commission', $this->integer(11)->notNull()->defaultValue(0)->comment('商品店铺分成比例'));
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'shop_share_commission');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181128_022608_alert_goods_shop_share_commission cannot be reverted.\n";

        return false;
    }
    */
}
