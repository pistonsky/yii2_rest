<?php

use yii\db\Schema;
use yii\db\Migration;

class m150224_092846_add_lang_column_to_questions_table extends Migration
{
    public function up()
    {
    	$this->addColumn('questions', 'lang', Schema::TYPE_STRING . ' NOT NULL');
    }

    public function down()
    {
        $this->dropColumn('questions', 'lang');
    }
}
