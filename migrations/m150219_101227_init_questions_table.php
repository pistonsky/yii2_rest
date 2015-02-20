<?php

use yii\db\Schema;
use yii\db\Migration;

class m150219_101227_init_questions_table extends Migration
{
    public function up()
    {
    	$this->createTable('questions', [
            'id' => 'bigpk',
            'user_id' => 'bigint', // question author
            'text' => 'text',
            'limit' => 'integer',
            'time' => 'bigint'
        ]);
    }

    public function down()
    {
        $this->dropTable('questions');
    }
}
