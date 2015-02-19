<?php

namespace app\controllers;

use app\models\Users;

class RegisterController extends Controller
{
    public function actionIndex()
    {
    	$model = new Users();

    	// TODO: generating encryption key
    	$model->key = md5(microtime(true));

    	$model->save();

    	$this->renderJSON([
    		'response' => [
    			'data' => [
    				'id' => $model->id,
    				'key' => $model->key
    			],
    			'time' => time()
    		]
    	]);
    }
}