<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m666666_666666_create_article_table extends Migration
{
    const TABLE = 'article';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'article_id' => $this->primaryKey(),
            'article_user_id' => $this->integer(11)->notNull(),
            'article_name' => $this->string(255)->notNull(),
            'article_text' => $this->text(255)->notNull(),
            'article_status' => $this->smallInteger()->notNull()->defaultValue(0),
            'article_created_at' => $this->datetime()->notNull(),
            'article_updated_at' => $this->datetime()->notNull()
        ]);

        $this->createIndex(
            'article_user_id',
            self::TABLE,
            'article_user_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'article_user_id',
            self::TABLE
        );

        $this->dropTable(self::TABLE);
    }
}
