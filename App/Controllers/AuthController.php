<?php

namespace App\Controllers;

use App\Config\Configuration;
use App\Core\AControllerBase;
use App\Core\Responses\Response;
use App\Core\Responses\ViewResponse;
use App\Models\User;

/**
 * Class AuthController
 * Controller for authentication actions
 * @package App\Controllers
 */
class AuthController extends AControllerBase
{
    /**
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->redirect(Configuration::LOGIN_URL);
    }

    /**
     * Login a user
     * @return Response
     */
    public function login(): Response
    {
        $formData = $this->app->getRequest()->getPost();
        $logged = null;
        if (isset($formData['submit'])) {
            $logged = $this->app->getAuth()->login($formData['login'], $formData['password']);
            if ($logged) {
                return $this->redirect($this->url("home.index"));
            }
        }

        $data = ($logged === false ? ['message' => 'Zlý login alebo heslo!'] : []);
        return $this->html($data);
    }

    /**
     * Sign up a user
     * @return Response
     */
    public function signup(): Response
    {
        $formData = $this->app->getRequest()->getPost();
        $logged = null;
        $registered = null;
        if (isset($formData['submit'])) {
            $registered = $this->app->getAuth()->signup($formData['login'], $formData['password']);

            if ($registered) {
                $logged = $this->app->getAuth()->login($formData['login'], $formData['password']);
                if ($logged) {
                    return $this->redirect($this->url("home.index"));
                }
            }
        }

        $data = ($registered === false ? ['message' => 'Tento login sa už používa!'] : []);
        return $this->html($data);
    }

    /**
     * Logout a user
     * @return ViewResponse
     */
    public function logout(): Response
    {
        $this->app->getAuth()->logout();
        return $this->redirect($this->url("home.index"));
    }
}
