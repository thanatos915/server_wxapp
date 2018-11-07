<?php

use yii\db\Migration;

/**
 * Handles the creation of table `dingshi`.
 */
class m181107_035630_create_dingshi_table extends Migration
{
    public $tableName = '{{%dingshi}}';
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'store_id' => $this->integer(11)->notNull()->comment('店铺 ID'),
            'start_time' => $this->string(32)->notNull()->defaultValue('')->comment('定时购开始时间'),
            'end_time' => $this->string(32)->notNull()->defaultValue('')->comment('定时购结束时间'),
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
