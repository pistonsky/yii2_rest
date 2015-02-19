<?php

namespace app\models;

class Message extends \yii\db\ActiveRecord
{
	public static function tableName()
    {
        return 'messages';
    }
}