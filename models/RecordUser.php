<?php

namespace app\models;

use Yii;
use \yii\db\ActiveRecord;
/**
 * This is the model class for table "record_user".
 *
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property string $avatarimage
 */
class RecordUser extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'record_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname'], 'required'],
            [['firstname', 'lastname'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
        ];
    }

    public function getUsers()
    {
        return $this->hasOne(User::className(), ['id' => 'id']);
    }
}
