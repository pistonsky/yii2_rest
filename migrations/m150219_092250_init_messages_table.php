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
            'thread' => 'bigint NULL', // if it is answer, then this is id of the first open message
            'to' => 'bigint NULL', // NULL if open message, otherwise user_id of the one who is the message for
            'limit' => 'integer',
            'time' => 'bigint'
        ]);
    }

    public function down()
    {
        $this->dropTable('messages');
    }
}
