<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "article".
 *
 * @property int $article_id
 * @property int $article_user_id
 * @property int $article_plan_id
 * @property string $article_name
 * @property string $article_text
 * @property int $article_status
 * @property string $article_created_at
 * @property string $article_updated_at
 */
class Article extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article_user_id', 'article_name', 'article_text', 'article_created_at', 'article_updated_at'], 'required'],
            [['article_user_id', 'article_status'], 'integer'],
            [['article_text'], 'string'],
            [['article_created_at', 'article_updated_at'], 'safe'],
            [['article_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'article_id' => 'Article ID',
            'article_user_id' => 'Article User ID',
            'article_name' => 'Article Name',
            'article_text' => 'Article Text',
            'article_status' => 'Article Status',
            'article_created_at' => 'Article Created At',
            'article_updated_at' => 'Article Updated At',
        ];
    }

    public function defaultSettings($model)
    {
        $model->article_user_id = Yii::$app->user->identity->id;
        $model->article_created_at = date('Y-m-d H:i:s');
        $model->article_updated_at = date('Y-m-d H:i:s');
        $model->article_status = self::STATUS_DISABLE;

        return $model;
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'article_user_id']);
    }
}
