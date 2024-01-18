<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Core\Responses\Response;
use App\Models\Post;
use App\Models\SavedPost;

class UserController extends AControllerBase
{
    public function index(): Response
    {
    }

    public function posts(): Response
    {
        return $this->html(['posts' => Post::getAll(whereClause: ("author = '" . $this->app->getAuth()->getLoggedUserName() . "'"), orderBy: "date desc")]);
    }

    public function saved(): Response
    {
        $saved = SavedPost::getAll(whereClause: ("save_author = '" . $this->app->getAuth()->getLoggedUserName() . "'"));

        if (empty($saved)) {
            return $this->html();
        }

        return $this->html(['posts' => Post::getAll(whereClause: ("author = '" . $this->app->getAuth()->getLoggedUserName() . "'"), orderBy: "date desc")]);
    }
}