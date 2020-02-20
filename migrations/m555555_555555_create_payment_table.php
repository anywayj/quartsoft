<?php

use yii\db\Migration;

/**
 * Handles the creation of table `payment`.
 */
class m555555_555555_create_payment_table extends Migration
{
    const TABLE = 'payment';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'payment_id' => $this->primaryKey(),
            'payment_user_id' => $this->integer(11)->notNull(),
            'payment_plan_id' => $this->integer(11)->notNull(),
            'payment_created_at' => $this->datetime()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
