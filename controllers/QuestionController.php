<?php

namespace app\controllers;

use app\models\Question;
use app\models\Message;

class QuestionController extends Controller
{
    public function actionIndex()
    {
        list($lang) = $this->checkInputParameters(['lang']);

    	$user = $this->getUser();

        $questions_this_user_ever_answered = Message::find()->select(['question_id'])->where(['user_id'=>$user->id])->distinct()->column();

    	$questions = Question::find()->where('user_id!='.$user->id)->andWhere(['not in','id',$questions_this_user_ever_answered])->andWhere(['lang'=>$lang])->orderBy('id DESC')->limit(MAX_LAST_QUESTIONS_TO_SHUFFLE_FROM_IN_GET_QUESTIONS)->asArray()->all();

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

    public function actionDelete()
    {
        $this->error(UnderDevelopment,'this method is not yet developed');
    }
}