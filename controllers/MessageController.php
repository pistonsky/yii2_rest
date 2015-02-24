<?php

namespace app\controllers;

use app\models\Message;
use app\models\Question;

class MessageController extends Controller
{
    public function actionIndex()
    {
    	list($version) = $this->checkInputParameters(['version']); 

    	$user = $this->getUser();

    	// first - find all my questions
    		$my_questions_model = Question::find()->where(['user_id'=>$user->id])->all();
    		$questions = [];
    		foreach ($my_questions_model as $q)
    			$questions[] = $q->id;

    	// second - find all messages that have greater id than version, and that belong to my questions or written to me
    		$messages = Message::find()->where(['question_id'=>$questions])->andWhere('id>'.$version)->orWhere(['to'=>$user->id])->andWhere('id>'.$version)->asArray()->all();


    	$this->renderJSON([
    		'response' => [
    			'data' => [
    				'messages' => $messages
    			]
    		]
    	]);
    }

    public function actionAdd()
    {
    	list($text,$question_id,$time) = $this->checkInputParameters(['text','question_id','time']); 

    	$user = $this->getUser();

    	$model = new Message();

    	$model->user_id = $user->id;
    	$model->text = $text;
    	$model->question_id = $question_id;
    	$model->to = \Yii::$app->request->post('to', null);
    	$model->time = $time;
    	if ($model->save())
        {
            $this->renderJSON([
                'response' => [
                    'data' => [
                        'id' => $model->id
                    ]
                ]
            ]);
        } else {
            $this->error(SaveFailed, "message save failure");
        }
    }
}