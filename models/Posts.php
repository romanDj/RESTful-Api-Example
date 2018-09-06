<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "posts".
 *
 * @property int $id
 * @property string $title
 * @property string $anons
 * @property string $text
 * @property string $tags
 * @property string $image
 */
class Posts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posts';
    }




    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'anons', 'text', 'tags'], 'string'],
            [['title', 'anons', 'text', 'image'],'required'],
            [['title'], 'unique',  'targetAttribute' => 'title'],

            [['image'], 'file', 'extensions' => ['png', 'jpg'], 'maxSize' => 2048*2048, 'message' => 'Картинка должна быть в формате png или jpg и не больше 2Mb'],

        ];
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'anons' => 'Anons',
            'text' => 'Text',
            'tags' => 'Tags',
            'image' => 'Image',
        ];
    }

    public static function getAll(){
        return Posts::find()->all();
    }


    //связь для просмотра комментов
    public function getComments(){
        return $this->hasMany(Comment::className(), [ 'posts_id'=> 'id']);
    }

}
