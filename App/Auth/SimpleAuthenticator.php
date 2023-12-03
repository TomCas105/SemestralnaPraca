<?php

namespace App\Auth;

use App\Auth\DummyAuthenticator;

class SimpleAuthenticator extends DummyAuthenticator
{
    public function login($login, $password): bool
    {
        if ($login == $password) {
            $_SESSION['user'] = $login;
            return true;
        } else {
            return false;
        }
    }
}