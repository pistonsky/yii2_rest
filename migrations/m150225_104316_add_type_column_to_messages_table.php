<?php

use yii\db\Schema;
use yii\db\Migration;

class m150225_104316_add_type_column_to_messages_table extends Migration
{
    public function up()
    {
    	$this->addColumn('messages', 'type', Schema::TYPE_INTEGER . ' NOT NULL');
    }

    public function down()
    {
        $this->dropColumn('messages', 'type');
    }
}
