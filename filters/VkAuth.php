<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\filters;

/**
 * QueryParamAuth is an action filter that supports the authentication based on the access token passed through a query parameter.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class VkAuth extends \yii\filters\auth\AuthMethod
{
    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        $uid = $request->post('uid');
        $auth_key = $request->post('auth_key');
        $sid = $request->post('sid');

        if (!empty($uid) && !empty($auth_key)) {
            $identity = $user->loginByAuthKey($uid, $auth_key, $sid, get_class($this));
            if ($identity !== null) {
                return $identity;
            }
        }
        if (($uid !== null) && ($auth_key !== null)) {
            header('Content-type: application/json');
            echo json_encode([
                'error' => [
                    'code' => WrongAuthKey,
                    'msg' => "auth_key is wrong for uid " . $uid
                ]
            ]);
        }

        return null;
    }
}
