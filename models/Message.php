<?php

namespace app\models;

class Message extends \yii\db\ActiveRecord
{
	const TYPE_MESSAGE = 0;
	const TYPE_DELETE = 1;

	public static function tableName()
    {
        return 'messages';
    }
}