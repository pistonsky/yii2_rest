<?php

namespace app\components;

use app\models\Admins;
use app\models\Users;

class User extends \yii\web\User
{
    public $sid;

	public function loginByAuthKey($uid, $auth_key, $sid, $type)
	{
        $this->sid = $sid;
		/* @var $class IdentityInterface */
        $class = $this->identityClass;
        $identity = $class::findIdentityByUidAuthKey($uid, $auth_key, $type);
        if ($identity && $this->login($identity)) {
            return $identity;
        } else {
            return null;
        }
	}

    public function adminLoginByAuthKey($auth_key)
    {
        return Admins::findOne(['auth_key' => $auth_key]);
    }

    public function loginByUid($uid)
    {
        $class = $this->identityClass;
        $identity = $class::findIdentity($uid);
        if ($identity && $this->login($identity)) {
            return $identity;
        } else {
            return null;
        }
    }
}