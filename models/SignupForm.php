<?php
 
namespace app\models;
 
use Yii;
use yii\base\Model;
 
/**
 * Signup form
 */
class SignupForm extends Model
{
 
    public $username;
    public $password;
    public $created_at;
    public $updated_at;
 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            [['username','password'], 'required','message'=>Yii::t('app', 'Не может быть пустым')],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Этот логин уже существует'],
            [['username','password'], 'string', 'min' => 5, 'max' => 255],
            [['created_at','updated_at'],'safe'],
        ];
    }
	
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'password' => 'Пароль',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
 
        if (!$this->validate()) {
            return null;
        }
 
        $user = new User();
        $user->username = $this->username;
        $user->created_at = $this->created_at;
        $user->updated_at = $this->updated_at;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        return $user->save() ? $user : null;
    }
 
}