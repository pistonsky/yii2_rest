<?php

namespace app\models;

class Question extends \yii\db\ActiveRecord
{
	public static function tableName()
    {
        return 'questions';
    }
}