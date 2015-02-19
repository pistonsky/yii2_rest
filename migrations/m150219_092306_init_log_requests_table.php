<?php

use yii\db\Schema;
use yii\db\Migration;

class m150219_092306_init_log_requests_table extends Migration
{
    public function up()
    {
    	$this->createTable('log_requests', [
            'id' => 'bigpk',
            'timestamp' => 'bigint'
        ]);
    }

    public function down()
    {
        $this->dropTable('log_requests');
    }
}
