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
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }




    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['author', 'comment'], 'string'],
            [['comment'], 'string', 'max' => 255],
            [['author', 'comment'], 'required'],

        ];
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'comment_id' => 'Comment_ID',
            'author' => 'Author',
            'comment' => 'Comment',
            'datatime' => 'Datatime',
            'post_id' => 'Post_ID'
        ];
    }


}
