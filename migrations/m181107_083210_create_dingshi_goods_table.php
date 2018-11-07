<?php

use yii\db\Migration;

/**
 * Handles the creation of table `dingshi_goods`.
 */
class m181107_083210_create_dingshi_goods_table extends Migration
{

    public $tableName = "{{%dingshi_goods}}";

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'store_id' => $this->integer(11)->notNull()->comment('店铺 ID'),
            'goods_id' => $this->integer(11)->notNull()->comment('商品 ID'),
            'open_date' => $this->date()->comment('开放日期'),
            'attr' => $this->text()->notNull()->comment('规格数量'),
            'created_at' => $this->integer(11)->notNull()->defaultValue(0)->comment('添加时间'),
            'updated_at' => $this->integer(11)->notNull()->defaultValue(0)->comment('修改时间')
        ]);

        $this->createIndex('idx-store_id-open_date', $this->tableName, ['store_id', 'open_date']);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
