<?php

use yii\db\Schema;
use yii\db\Migration;

class m150219_092227_init_users_table extends Migration
{
    public function up()
    {
    	$this->createTable('users', [
            'id' => 'bigpk',
            'key' => 'string',
            'version' => 'bigint',
            'allowance' => 'integer',
            'last_request' => 'integer'
        ]);
    }

    public function down()
    {
        $this->dropTable('users');
    }
}
