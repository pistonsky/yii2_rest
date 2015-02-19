<?php

namespace app\controllers;

use yii\rest\ActiveController;

class MessageController extends ActiveController
{
    public $modelClass = 'app\models\Message';
}