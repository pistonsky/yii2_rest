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
    		'questions' => $questions
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
    		'id' => $question->id
    	]);
    }

    public function actionDelete()
    {
        list($id) = $this->checkInputParameters(['id']);
        $user = $this->getUser();
        if ($question = Question::findOne($id))
        {
            // find out if I am the author
            if ($user->id == $question->user_id)
            {
                $user_id = $this->post('user_id', null);
                if ($user_id === null)
                {
                    // deleting the whole question
                    $message = new Message();
                    $message->user_id = 0;
                    $message->text = $this->post('text','');
                    $message->question_id = $id;
                    $message->type = Message::TYPE_DELETE;
                    $message->time = time();

                    $result = $message->save();
                } else {
                    // deleting chat with user_id
                    $message = new Message();
                    $message->user_id = $user->id;
                    $message->to = $user_id;
                    $message->text = $this->post('text','');
                    $message->question_id = $id;
                    $message->type = Message::TYPE_DELETE;
                    $message->time = time();

                    $result = $message->save();
                }
            } else {
                // I am not the author - I just want to close chat
                $message = new Message();
                $message->user_id = $user->id;
                $message->text = $this->post('text','');
                $message->question_id = $id;
                $message->type = Message::TYPE_DELETE;
                $message->time = time();

                $result = $message->save();
            }
            $this->renderJSON([
                'result' => $result
            ]);
        } else {
            $this->error(InvalidParameter,"question #$id doesn't exist");
        }
    }
}