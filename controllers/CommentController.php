<?php
/**
 * Created by PhpStorm.
 * User: WSR-1
 * Date: 03.09.2018
 * Time: 14:28
 */

namespace app\controllers;


use app\models\Comment;
use app\models\Posts;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\Cors;
use yii\rest\ActiveController;
use yii\rest\Controller;
use yii\web\UploadedFile;

class CommentController extends ActiveController
{
    public $modelClass = 'app\models\Comment';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
            // 'Origin' => ['*'],
            //'Access-Control-Request-Headers' => ['*'],
            // 'Access-Control-Allow-Credentials' => true,
        ];


        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'optional' => ['delete','add-comment']
        ];
//        $behaviors['authenticator']['class'] = HttpBearerAuth::className();
//        $behaviors['authenticator']['optional'] = ['delete'];
        //$behaviors['authenticator']['optional'] = ['delete'];

         //$behaviors['authenticator']['except'] = ['add-comment',/* 'add-comment'*/];


        return $behaviors;
    }



    public function actions()
    {
        $actions = parent::actions();

        unset($actions['index']);
        unset($actions['view']);

        unset($actions['create']);
        unset($actions['delete']);
        unset($actions['update']);

        return $actions;
    }


    //добавление комментариев
    public function  actionAddComment($post_id){


        $post = Posts::find()->where(['id' => $post_id])->one();

        if($post!=null){

            $model = new Comment();
            if($model->load(Yii::$app->request->post(), "")){

                if(!Yii::$app->user->isGuest){
                    $model->author = Yii::$app->user->identity->login;
                }


                if($model->validate()){

                    Yii::$app->response->statusCode = 201;
                    Yii::$app->response->statusText = "Successful creation";

                    $model->datatime = date('Y-m-d h:i');
                    $model->posts_id = $post->id;
                    $model->save();
                    return [ 'status' => true ];

                }else{

                    Yii::$app->response->statusCode = 400;
                    Yii::$app->response->statusText = "Creating error";
                    return [
                        'status' => false,
                        'message' => $model->errors ];

                }

            }
            return $model->errors;

        }else{

            Yii::$app->response->statusCode = 404;
            Yii::$app->response->statusText = "Post not found";
            return [ 'message' => 'Post not found'];

        }


    }

    //удаление комментария
    public function actionDelete($post_id, $comment_id){

        if(Yii::$app->user->identity->isAdmin){

            $post = Posts::find()->where(['id' => $post_id])->one();
            $comment = Comment::find()->where(['comment_id' => $comment_id])->one();
            if($comment == null){
                Yii::$app->response->statusCode = 404;
                Yii::$app->response->statusText = "Comment not found";
                return [ 'message' => "Comment not found" ];
            }

            elseif($post == null){
                Yii::$app->response->statusCode = 404;
                Yii::$app->response->statusText = "Post not found";
                return ['message' => "Post not found"];
            }

            else{

                Yii::$app->response->statusCode = 201;
                Yii::$app->response->statusText = "Successful delete";

                Comment::find()->where(['comment_id' => $comment_id])->
                andWhere(['posts_id'=>$post_id])->one()->delete();

                return [ 'status' => true ];
            }

        }else{
            Yii::$app->response->statusCode = 401;
            Yii::$app->response->statusText = 'Unauthorized';
            return[
                'status' => false,
                'message' => 'Unauthorized'
            ];
        }



    }




}