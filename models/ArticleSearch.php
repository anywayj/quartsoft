<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Article;

/**
 * ArticleSearch represents the model behind the search form of `app\models\Article`.
 */
class ArticleSearch extends Article
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article_id', 'article_user_id', 'article_status'], 'integer'],
            [['article_name', 'article_text', 'article_created_at', 'article_updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Article::find()->where(['article_user_id' => Yii::$app->user->identity->id]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'article_id' => $this->article_id,
            'article_user_id' => $this->article_user_id,
            'article_status' => $this->article_status,
            'article_created_at' => $this->article_created_at,
            'article_updated_at' => $this->article_updated_at,
        ]);

        $query->andFilterWhere(['like', 'article_name', $this->article_name])
            ->andFilterWhere(['like', 'article_text', $this->article_text]);

        return $dataProvider;
    }

    public function searchMain($params)
    {
        $query = Article::find()->where(['article_status' => Article::STATUS_ACTIVE]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'article_user_id' => $this->article_user_id,
        ]);

        return $dataProvider;
    }
}
