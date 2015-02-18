<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "locale".
 *
 * @property string $id
 * @property string $locale
 * @property string $text
 */
class Locale extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'locale';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'text'], 'required'],
            [['text'], 'string'],
            [['id'], 'string', 'max' => 255],
            [['locale'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'например \"boosts.names.1\"',
            'locale' => '\"ru\", \"en\"',
            'text' => 'Сама строка в нужном языке',
        ];
    }
}
