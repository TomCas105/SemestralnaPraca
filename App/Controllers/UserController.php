<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Core\Responses\Response;
use App\Models\Post;

class UserController extends AControllerBase
{
    public function index(): Response
    {
    }

    public function posts(): Response
    {
        return $this->html(['posts' => Post::getAll(whereClause: ("author = '" . $this->app->getAuth()->getLoggedUserName() . "'"), orderBy: "date desc")]);
    }
}