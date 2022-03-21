<?php

namespace Validators;

use Valitron\Validator;

class AuthValidator {
    /**
     * @return string|null
     */
    public static function validateSignup(object $data)
    {
        $v = new Validator((array) $data);
        $v->rule('required', ['name', 'email', 'password']);
        $v->rule('email', ['email']);
        $v->rule('lengthMin', ['password'], 6);

        if (!$v->validate()) return array_shift($v->errors())[0];
        else return null;
    }

    /**
     * @return string|null
     */
    public static function validateLogin(object $data)
    {
        $v = new Validator((array) $data);
        $v->rule('required', ['email', 'password']);
        $v->rule('email', ['email']);

        if (!$v->validate()) return array_shift($v->errors())[0];
        else return null;
    }

    /**
     * @return string|null
     */
    public static function validateUpdateMe(object $data)
    {
        $v = new Validator((array) $data);
        $v->rule('lengthMin', ['password'], 6);

        if (!$v->validate()) return array_shift($v->errors())[0];
        else return null;
    }
}