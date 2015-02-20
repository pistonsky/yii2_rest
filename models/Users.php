<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property string $id
 * @property string $key
 * @property integer $allowance
 * @property integer $last_request
 *
 */
class Users extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface/*, \yii\filters\RateLimitInterface*/
{   
    public static function tableName()
    {
        return 'users';
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->key;
    }

    public function validateAuthKey($authKey)
    {
        return true;
    }
    
    // uncomment next two functions if using mongodb

    /**
     * @inheritdoc
     */
    // public function getId()
    // {
    //     return $this->user_id; // for mysql
    //     // return $this->_id; // for mongodb
    // }

    /**
     * @inheritdoc
     */
    // public function setId($id)
    // {
    //     $this->user_id = $id; // for mysql
    //     // $this->_id = $id; // for mongodb
    // }

    /**
     * For mongodb
     * @return string the name of the index associated with this ActiveRecord class.
     */
    // public static function collectionName()
    // {
    //     return 'user';
    // }

    /**
     * If redis, put "id", if mongo, put "_id", if mysql, put "user_id"
     * @return array the list of attributes for this record
     */
    // public function attributes()
    // {
    //     return ['id', 'key', 'allowance', 'last_request'];
    // }

    /**
     * @inheritdoc
     */
    public function getRateLimit($request, $action)
    {
        return [RATE_LIMIT_PER_USER_PER_SECOND, 1]; // set in config/constants.php
    }

    /**
     * @inheritdoc
     */
    public function loadAllowance($request, $action)
    {
        $model = static::findOne($request->post('uid'));
        return [$model['allowance'], $model['last_request']];
    }

    /**
     * @inheritdoc
     */
    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        $model = static::findOne($request->post('uid'));
        $model->allowance = $allowance;
        $model->last_request = $timestamp;
        $model->update();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $uid = $_POST['uid'];
        return static::findOne($uid);
    }
}
