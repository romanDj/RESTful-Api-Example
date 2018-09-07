<?php
/**
 * Created by PhpStorm.
 * User: WSR-1
 * Date: 03.09.2018
 * Time: 14:28
 */

namespace app\controllers;


use app\models\Posts;

use Yii;
use yii\data\ActiveDataProvider;
use yii\debug\models\timeline\DataProvider;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\Cors;
use yii\helpers\Url;
use yii\rest\ActiveController;
use yii\web\UploadedFile;

class PostsController extends ActiveController
{
    public $modelClass = 'app\models\Posts';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
           // 'Origin' => ['*'],
            //'Access-Control-Request-Headers' => ['*'],
           // 'Access-Control-Allow-Credentials' => true,
        ];



        $behaviors['authenticator']['class'] = HttpBearerAuth::className();
        $behaviors['authenticator']['optional'] = ['create','update-post', 'delete'];

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

    //просмотр всех записей
    public function  actionIndex(){
        Yii::$app->response->statusCode = 200;
        Yii::$app->response->statusText = 'List posts';
        return new ActiveDataProvider([
            'query' => Posts::find()
        ]);
    }

    //просмотр одной записи
    public function actionView($post_id){
        $query = Posts::find()->where(['id'=>$post_id])->one();
        if($query != null){
            Yii::$app->response->statusCode = 200;
            Yii::$app->response->statusText = 'View post';
            return [
                'title' => $query->title,
                'datatime' => $query->date,
                'anons' => $query->anons,
                'text' => $query->text,
                'tags' => $query->tags,
                'images' => $query->images,
                'comments' => $query->comments
            ];

        }else{
            Yii::$app->response->statusCode = 404;
            Yii::$app->response->statusText = 'Post not found';
            return [ 'message' => 'Post not found'];
        }
    }

    //поиск постов по тегу
    public function actionGet($tag)
    {
        Yii::$app->response->statusText = 'Found posts';
        return new ActiveDataProvider([
           'query' => Posts::find()->andFilterWhere(['like','tags', $tag])
        ]);
    }

    //создание поста
    public function actionCreate(){

        if(Yii::$app->user->identity->isAdmin){

            $model = new Posts();
            if($model->load(Yii::$app->request->post(), "")) {

                $model->image = UploadedFile::getInstanceByName('image');
                if($model->validate()){
                    $filename = uniqid().'.'.$model->image->extension;
                    $model->image->saveAs('api/post_images/'.$filename);
                    $model->image = $filename;
                    $model->datatime = date("Y-m-d H:i");
                    $model->save();

                    Yii::$app->response->statusCode = 201;
                    Yii::$app->response->statusText = 'Successful creation';

                    return [ 'status' => true, 'post_id' => $model->id ];

                }



            }

            Yii::$app->response->statusCode = 400;
            Yii::$app->response->statusText = 'Creating error';
            return [
                'status' => false,
                'message' => $model->errors
            ];

        }else{
            Yii::$app->response->statusCode = 401;
            Yii::$app->response->statusText = 'Unauthorized';
            return[
                'status' => false,
                'message' => 'Unauthorized'
            ];
        }


    }

    //изменение поста
    public function  actionUpdatePost($post_id){

        if(Yii::$app->user->identity->isAdmin){

            $model = Posts::find()->where(['id'=>$post_id])->one();
            $oldname = $model->image;
            $model->load(Yii::$app->request->post(), "");
            $model->image = UploadedFile::getInstanceByName('image');
            if($model->image != null){

                if($model->validate()){
                    unlink('api/post_images/'.$oldname);
                    $filename = uniqid().'.'.$model->image->extension;
                    $model->image->saveAs('api/post_images/'.$filename);
                    $model->image = $filename;
                    $model->save();
                    return "сохранено с новой фоткой";
                }

                Yii::$app->response->statusCode = 400;
                Yii::$app->response->statusText = 'Editing error';
                return[
                    'status' => false,
                    'message' => $model->errors
                ];


            }else{

                $model->image = $oldname;
                if($model->validate(['title', 'anons', 'text'])){
                    $model->save();
                    return "сохранено со старой фоткой";
                }

                Yii::$app->response->statusCode = 400;
                Yii::$app->response->statusText = 'Editing error';
                return[
                    'status' => false,
                    'message' => $model->errors
                ];

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

    //удаление поста
    public function  actionDelete($post_id){

        if(Yii::$app->user->identity->isAdmin){

            if($model = Posts::find()->where(['id' => $post_id])->one()){
                $model->delete();
                return ['status' => true];
            }else{
                return [ 'message' => 'Post not found' ];
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