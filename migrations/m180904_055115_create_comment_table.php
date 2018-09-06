<?php

use yii\db\Migration;

/**
 * Handles the creation of table `comment`.
 */
class m180904_055115_create_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('comment', [
            'comment_id' => $this->primaryKey(),
            'author' => $this->string(),
            'comment' => $this->string(),
            'datatime' => $this->dateTime(),
            'posts_id' => $this->integer()
        ]);

        $this->createIndex('idx-comment-posts','comment', 'posts_id');
        $this->addForeignKey('fx-posts','comment', 'posts_id', 'posts', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('comment');
    }
}
