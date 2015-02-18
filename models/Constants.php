<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "constants".
 *
 * @property string $name
 * @property string $type
 * @property string $value
 */
class Constants extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constants';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type', 'value'], 'required'],
            [['type'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['value'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'type' => 'Type',
            'value' => 'Value',
        ];
    }
}
