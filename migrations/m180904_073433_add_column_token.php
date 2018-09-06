<?php

use yii\db\Migration;

/**
 * Class m180904_073433_add_column_token
 */
class m180904_073433_add_column_token extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn('user','token','string');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180904_073433_add_column_token cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180904_073433_add_column_token cannot be reverted.\n";

        return false;
    }
    */
}
