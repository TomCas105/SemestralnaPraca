<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Core\HTTPException;
use App\Core\Responses\Response;
use App\Models\Post;
use App\Models\SavedPost;

class UserController extends AControllerBase
{
    public function index(): Response
    {
        throw new HTTPException(501, "Not Implemented");
    }

    public function posts(): Response
    {
        return $this->html(['posts' => Post::getAll(whereClause: ("author = '" . $this->app->getAuth()->getLoggedUserName() . "'"), orderBy: "date desc")]);
    }

    /**
     * @throws \Exception
     */
    public function saved(): Response
    {
        $savedPosts = SavedPost::getAll(whereClause: ("save_author = '" . $this->app->getAuth()->getLoggedUserName() . "'"));

        if (empty($savedPosts)) {
            return $this->html();
        }

        $ids = "";
        foreach ($savedPosts as $savedPost) {
            $ids .= "'" . $savedPost->getPostId() . "', ";
        }
        $ids = substr($ids, 0, strlen($ids) - 2);

        return $this->html(['posts' => Post::getAll(whereClause: "id in (" . $ids . ")", orderBy: "date desc")]);
    }
}