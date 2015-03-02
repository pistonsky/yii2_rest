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
    		$questions = Question::find()->where(['user_id'=>$user->id])->select('id')->column();

        // second - find all questions that I have answered
            $answered_questions = Message::find()->where(['user_id'=>$user->id])->distinct()->select('question_id')->column();

    	// second - find all messages that have greater id than version, and that belong to my questions or written to me
    		$messages = Message::find()->where(['question_id'=>$questions,'type'=>Message::TYPE_MESSAGE])->andWhere('id>'.$version)->orWhere(['to'=>$user->id,'type'=>Message::TYPE_MESSAGE])->andWhere('id>'.$version)->asArray()->all();

        // third - find recently deleted chats where I participated
            $deleted_questions = [];
            if ($deleted = Message::find()
                ->where(['type'=>Message::TYPE_DELETE,'question_id'=>$questions])
                ->andWhere('id>'.$version)

                ->orWhere(['to'=>$user->id,'type'=>Message::TYPE_DELETE])
                ->andWhere('id>'.$version)

                ->orWhere(['type'=>Message::TYPE_DELETE,'question_id'=>$answered_questions,'user_id'=>0])
                ->andWhere('id>'.$version)->all())
            {
                foreach ($deleted as $message) {
                    if ($message->user_id != $user->id) // don't tell me which chats have I deleted on my own - I know that
                    {
                        $deleted_question = [
                            'id' => $message->id,                   // now with ID
                            'question_id' => $message->question_id,
                            'time' => $message->time
                        ];
                        if (($message->user_id != 0) && ($message->user_id != $user->id))
                            $deleted_question['user_id'] = $message->user_id;
                        if ($message->text != '')
                            $deleted_question['text'] = $message->text;
                        $deleted_questions[] = $deleted_question;
                    }
                }
            }

    	$this->renderJSON([
				'messages' => $messages,
                'deleted_questions' => $deleted_questions
    	]);
    }

    public function actionAdd()
    {
    	list($text,$question_id,$time) = $this->checkInputParameters(['text','question_id','time']); 

    	$user = $this->getUser();

        // check if chat was closed before - if we have Message::TYPE_DELETE message for this question_id and user_id

        if (!$deleted_message_model = Message::find()->where(['type'=>Message::TYPE_DELETE,'question_id'=>$question_id,'user_id'=>[0,$user->id,$this->post('to', 0)]])->one())
        {
        	$model = new Message();

        	$model->user_id = $user->id;
        	$model->text = $text;
        	$model->question_id = $question_id;
        	$model->to = $this->post('to', null);
        	$model->time = time();
        	if ($model->save())
            {
                $this->renderJSON([
                    'id' => $model->id
                ]);
            } else {
                $this->error(SaveFailed, "message save failure");
            }
         } else {
            if ($deleted_message_model->user_id == 0)
                $this->error(ChatClosed, "this question and all conversations for it were deleted by question author");
            else
                $this->error(ChatClosed, "this conversation was deleted");
         }
   }

    public function actionDelete()
    {
        $this->error(UnderDevelopment,'this method is not yet developed');
    }
}