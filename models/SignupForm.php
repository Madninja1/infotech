<?php

namespace app\models;

use yii\base\Model;

class SignupForm extends Model
{
    public string $username = '';
    public string $password = '';

    public function rules(): array
    {
        return [
            [['username', 'password'], 'required'],
            ['username', 'string', 'min' => 3, 'max' => 64],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function signup(): ?User
    {
        if (!$this->validate()) {
            return null;
        }

        if (User::findByUsername($this->username)) {
            $this->addError('username', 'Пользователь уже существует.');
            return null;
        }

        $u = new User();
        $u->username = $this->username;
        $u->setPassword($this->password);
        $u->generateAuthKey();

        return $u->save() ? $u : null;
    }
}
