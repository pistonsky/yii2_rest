<?php

namespace app\models;

use Yii;

/**
 * Log every request into this table
 * Erase old log data > 15 minutes old
 * We need this model to monitor requests/minute metric
 *
 * @property integer $timestamp
 */

class LogRequests extends \yii\redis\ActiveRecord
{
    /**
     * @return array the list of attributes for this record
     */
    public function attributes()
    {
        return ['id','timestamp'];
    }
}