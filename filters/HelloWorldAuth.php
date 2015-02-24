<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\filters;

/**
 * HelloWorldAuth is an action filter that supports the authentication based on the uid passed in request parameters
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloWorldAuth extends \yii\filters\auth\AuthMethod
{
    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        $uid = $request->getAuthUser();

        if (!empty($uid)) {
            $identity = $user->loginByUid($uid);
            if ($identity !== null) {
                return $identity;
            }
        }
        if (($uid !== null)) {
            header('Content-type: application/json');
            echo json_encode([
                'error' => [
                    'code' => 401,
                    'msg' => "Unauthorized: can't find user with id #" . $uid
                ]
            ]);
        }

        return null;
    }
}
