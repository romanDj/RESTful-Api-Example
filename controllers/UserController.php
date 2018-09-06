<?php
/**
 * Created by PhpStorm.
 * User: WSR-1
 * Date: 03.09.2018
 * Time: 14:28
 */

namespace app\controllers;


use app\models\User;
use Yii;

use yii\filters\Cors;
use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = 'app\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),

        ];
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['index']);
        unset($actions['view']);
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);

        return $actions;
    }

    public function actionLogin()
    {
        $user = new User();
        $user->load(Yii::$app->request->post(),'');
        $user->scenario = 'login';
        return $user->login();
    }
}