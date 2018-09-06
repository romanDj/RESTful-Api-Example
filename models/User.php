<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $login
 * @property string $password
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    public function isReal($attribute, $params)
    {
        $user = self::findOne(['login' => $this->login]);

        if(!$user || $user->password != $this->password)
            $this->addError('login', 'login or password incorrect');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login', 'password'], 'required'],
            [['login'], 'isReal', 'on' => ['login']],
            [['login', 'password'], 'string', 'max' => 15],
            [['token'], 'string', 'max' => 32],
        ];
    }


    public function fields()
    {
//        return [
//            // название поля совпадает с именем атрибута
//
//            // название поля "email", атрибут "email_address"
//            'login' => 'login',
//            // название поля "name", значение определяется callback-ом PHP
//
//        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'password' => 'Password',
        ];
    }


    public static function findIdentity($id)
    {
        return static::findOne($id);
    }


    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token'=>$token]);
    }


    public function getId()
    {
        return $this->id;
    }


    public function getAuthKey()
    {
        return $this->authKey;
    }


    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public function login(){
        if($this->validate()){
            $user = self::findOne(['login' => $this->login]);
            $user->token = Yii::$app->security->generateRandomString();
            $user->save();
            Yii::$app->response->statusText = 'Successful authorization';
            return [
                'status' => true,
                'token' => $user->token
            ];
        }else{
            Yii::$app->response->statusCode = 401;
            Yii::$app->response->statusText = 'Invalid authorization data';
            return[
                'status' => false,
                'message' => 'Invalid authorization data'
            ];
        }
    }
}
