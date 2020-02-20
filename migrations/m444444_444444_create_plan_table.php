<?php

use yii\db\Migration;

/**
 * Handles the creation of table `plan`.
 */
class m444444_444444_create_plan_table extends Migration
{
    const TABLE = 'plan';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'plan_id' => $this->primaryKey(),
            'plan_name' => $this->string(255)->notNull(),
            'plan_price' => $this->double()->notNull(),
            'plan_pub_amount' => $this->integer(11)->notNull(),
            'plan_created_at' => $this->datetime()->notNull(),
            'plan_updated_at' => $this->datetime()->notNull()
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
