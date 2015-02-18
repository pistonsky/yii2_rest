<?php

namespace app\models;

use Yii;

/**
 * Модель, отвечающая за логирование транзакций - т.е. покупок золотых монет за реальные деньги (голоса)
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $transaction_sum
 * @property string $date
 *
 * @property Users $user
 */
class LogSession extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_session';
    }

    /**
     * Логирование сессий
     */
    public static function log($sid = NULL, $session_timeout = NULL, $ref = "unknown")
    {
        if (!$session_timeout) {
            $session_timeout_model = Constants::findOne('session_timeout');
            $session_timeout = $session_timeout_model->value;
        }

        if ($sid) {
            // TODO: обновлять инфу об уже начатой сессии (будет вызываться на каждом запросе)
            $user_model = \Yii::$app->user->identity;
            $model = LogSession::find()->where(['user_id'=>$user_model->id,'sid'=>$sid])->one();
            $model->authorize_end = time() + $session_timeout;
            $model->session_time = $model->authorize_end - $model->authorize_time;
            $model->save();
        } else {
            // создаем сессию - ее номер должен быть +1 от максимального номера
            $user_model = \Yii::$app->user->identity;
            $max_sid = LogSession::find()->where(['user_id'=>$user_model->id])->max('sid');
            $model = new LogSession();
            $model->sid = $max_sid + 1;
            $sid = $model->sid;
            $model->user_id = $user_model->id;
            $model->authorize_time = time();
            $model->authorize_end = time() + $session_timeout;
            $model->ref = $ref;
            $model->session_time = $session_timeout;
            $model->save();
        }

        return $sid;
    }
}
