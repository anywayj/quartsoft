<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "plan".
 *
 * @property int $plan_id
 * @property string $plan_name
 * @property double $plan_price
 * @property int $plan_pub_amount
 * @property string $plan_created_at
 * @property string $plan_updated_at
 */
class Plan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plan_name', 'plan_price', 'plan_pub_amount', 'plan_created_at', 'plan_updated_at'], 'required'],
            [['plan_price'], 'number'],
            [['plan_pub_amount'], 'integer'],
            [['plan_created_at', 'plan_updated_at'], 'safe'],
            [['plan_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'plan_id' => 'Plan ID',
            'plan_name' => 'Plan Name',
            'plan_price' => 'Plan Price',
            'plan_pub_amount' => 'Plan Pub Amount',
            'plan_created_at' => 'Plan Created At',
            'plan_updated_at' => 'Plan Updated At',
        ];
    }
}
