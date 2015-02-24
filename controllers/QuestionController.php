<?php

namespace app\controllers;

use app\models\Question;

class QuestionController extends Controller
{
    public function actionIndex()
    {
        list($lang) = $this->checkInputParameters(['lang']);

    	$user = $this->getUser();

    	$questions = Question::find()->where('user_id!='.$user->id)->andWhere(['lang'=>$lang])->orderBy('id DESC')->limit(MAX_LAST_QUESTIONS_TO_SHUFFLE_FROM_IN_GET_QUESTIONS)->asArray()->all();

    	shuffle($questions);
    	$questions = array_slice($questions, 0, MAX_QUESTIONS_IN_GET_QUESTIONS);

    	$this->renderJSON([
    		'response' => [
    			'data' => [
    				'questions' => $questions
    			]
    		]
    	]);
    }

    public function actionAdd()
    {
    	list($text,$limit,$lang) = $this->checkInputParameters(['text','limit','lang']);

    	$user = $this->getUser();

    	$question = new Question();
    	$question->user_id = $user->id;
    	$question->text = $text;
    	$question->limit = $limit;
        $question->lang = $lang;
    	$question->time = time();

    	$result = $question->save();

    	$this->renderJSON([
    		'response' => [
    			'data' => [
    				'id' => $question->id
    			]
    		]
    	]);
    }
}