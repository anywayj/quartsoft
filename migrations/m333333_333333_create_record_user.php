<?php

use yii\db\Migration;

/**
 * Handles the creation of table `record user`.
 */
class m333333_333333_create_record_user extends Migration
{
    const TABLE = 'record_user';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'firstname' => $this->string(255)->notNull(),
            'lastname' => $this->string(255)->notNull()
        ]);

        $this->insert(self::TABLE, [
            'firstname' => 'Валерий',
            'lastname' => 'Гриценко',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete(self::TABLE, ['id' => 1]);

        $this->dropTable(self::TABLE);
    }
}
