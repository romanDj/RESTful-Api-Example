<?php

use yii\db\Migration;

/**
 * Handles the creation of table `posts`.
 */
class m180903_111621_create_posts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('posts', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'anons' => $this->string(),
            'text' => $this->string(),
            'tags' => $this->string(),
            'image' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('posts');
    }
}
