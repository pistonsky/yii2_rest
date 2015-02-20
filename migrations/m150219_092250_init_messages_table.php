<?php

use yii\db\Schema;
use yii\db\Migration;

class m150219_092250_init_messages_table extends Migration
{
    public function up()
    {
    	$this->createTable('messages', [
            'id' => 'bigpk',
            'user_id' => 'bigint', // message author
            'text' => 'text',
            'question_id' => 'bigint NULL',
            'to' => 'bigint NULL', // user_id of the one who is the message for
            'time' => 'bigint'
        ]);
    }

    public function down()
    {
        $this->dropTable('messages');
    }
}
