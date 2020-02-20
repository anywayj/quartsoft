<?php
 
namespace app\models;
 
use Yii;
use yii\base\Model;
 
/**
 * Signup form
 */
class SignupFormtwo extends Model
{
    public $firstname;
    public $lastname;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firstname','lastname'], 'trim'],
            [['firstname','lastname'], 'required','message' => Yii::t('app', 'Не может быть пустым')],
            [['firstname','lastname'], 'string', 'min' => 3, 'max' => 255],
           
            
        ];
    }


    public function attributeLabels()
    {
        return [
            'firstname' => 'Имя',
            'lastname' => 'Фамилия',
        ];
    }
	
	
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signupTwo()
    {
        if (!$this->validate()) {
            return null;
        }
 
        $user = new RecordUser();
        $user->firstname = $this->firstname;
        $user->lastname = $this->lastname;
       
        return $user->save() ? $user : null;
    }
 
}