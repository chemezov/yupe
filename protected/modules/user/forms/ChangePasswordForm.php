<?php
class ChangePasswordForm extends CFormModel
{
    public $password;
    public $cPassword;

    public function rules()
    {
        return array(
            array('password, cPassword', 'required'),
            array('password, cPassword', 'length', 'min' => Yii::app()->getModule('user')->minPasswordLength),
            array('password', 'compare', 'compareAttribute' => 'cPassword', 'message' => Yii::t('UserModule.user', 'Password is not coincide!')),
        );
    }

    public function attributeLabels()
    {
        return array(
            'password'  => Yii::t('UserModule.user', 'New password'),
            'cPassword' => Yii::t('UserModule.user', 'Confirm new password'),
        );
    }
}