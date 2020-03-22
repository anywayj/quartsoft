<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payment".
 *
 * @property int $payment_id
 * @property int $payment_user_id
 * @property int $payment_plan_id
 * @property string $payment_created_at
 */
class Payment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_user_id', 'payment_plan_id', 'payment_created_at'], 'required'],
            [['payment_user_id', 'payment_plan_id'], 'integer'],
            [['payment_created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'payment_id' => 'Payment ID',
            'payment_user_id' => 'Payment User ID',
            'payment_plan_id' => 'Payment Plan ID',
            'payment_created_at' => 'Payment Created At',
        ];
    }

    public function savePayment($planId)
    {
        $payment = new Payment();
        $payment->payment_user_id = Yii::$app->user->id;
        $payment->payment_plan_id = $planId;
        $payment->payment_created_at = date('Y-m-d H:i:s');
        $payment->save();

        return $payment;
    }

    public function getCurrentUserPayments()
    {
       return self::find()->where(['payment_user_id' => Yii::$app->user->id])->all();
    }

    public function getPlan()
    {
        return $this->hasOne(Plan::className(), ['plan_id' => 'payment_plan_id']);
    }
}
