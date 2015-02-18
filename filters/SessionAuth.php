<?php

namespace app\filters;

use Yii;

/**
 * SessionAuth is an action filter that supports the authentication based on the access token passed through the PHP session.
 *
 * @author John Pistonsky <pistonsky@icloud.com>
 * @since 2.0
 */
class SessionAuth extends \yii\filters\auth\HttpBasicAuth
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $response = $this->response ? : Yii::$app->getResponse();

        $identity = $this->authenticate(
            $this->user ? : Yii::$app->getUser(),
            $this->request ? : Yii::$app->getRequest(),
            $response
        );

        if ($identity !== null) {
            return true;
        } else {
            $this->challenge($response);
            return false;
        }
    }
    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        $username = $request->getAuthUser();
        $password = $request->getAuthPassword();
        if ($this->auth) {
            if ($username !== null || $password !== null) {
                $identity = call_user_func($this->auth, $username, $password);
                if ($identity !== null) {
                    $user->switchIdentity($identity);
                }
                return $identity;
            }
        } elseif ($username !== null) {
            $identity = $user->loginByAccessToken($username, get_class($this));
            if ($identity === null) {
                $this->handleFailure($response);
            }
            return $identity;
        }
        return null;
    }
}
