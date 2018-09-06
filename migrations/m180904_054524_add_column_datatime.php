<?php

use yii\db\Migration;

/**
 * Class m180904_054524_add_column_datatime
 */
class m180904_054524_add_column_datatime extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('posts','datatime', 'datetime');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180904_054524_add_column_datatime cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180904_054524_add_column_datatime cannot be reverted.\n";

        return false;
    }
    */
}
