<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Core\HTTPException;
use App\Core\Responses\RedirectResponse;
use App\Core\Responses\Response;
use App\Helpers\FileStorage;
use App\Models\Post;
use App\Models\Review;
use App\Core\DB\Connection;
use App\Core\Model;
use http\Exception;
use PDO;

class PostController extends AControllerBase
{


    public function index(): Response
    {
        $id = (int)$this->request()->getValue('id');
        $post = Post::getOne($id);

        if (is_null($post)) {
            throw new HTTPException(404);
        }

        return $this->html(['post' => $post]);
    }

    public function authorize($action): bool
    {
        switch ($action) {
            case 'edit' :
            case 'delete' :
                // get id of post to check
                $id = (int)$this->request()->getValue("id");
                // get post from db
                $postToCheck = Post::getOne($id);
                // check if the logged login is the same as the post author
                // if yes, he can edit and delete post
                return $postToCheck->getAuthor() == $this->app->getAuth()->getLoggedUserName();
            case 'save':
                // get id of post to check
                $id = (int)$this->request()->getValue("id");
                if ($id > 0 ) {
                    // only author can save the edited post
                    $postToCheck = Post::getOne($id);
                    return $postToCheck->getAuthor() == $this->app->getAuth()->getLoggedUserName();
                } else {
                    // anyone can add a new post
                    return $this->app->getAuth()->isLogged();
                }
            default:
                return $this->app->getAuth()->isLogged();
        }
    }

    public function add(): Response
    {
        return $this->html();
    }

    public function edit(): Response
    {
        $id = (int)$this->request()->getValue('id');
        $post = Post::getOne($id);

        if (is_null($post)) {
            throw new HTTPException(404);
        }

        return $this->html(
            [
                'post' => $post
            ]
        );
    }

    public function save()
    {
        $id = (int)$this->request()->getValue('id');
        $oldFileName = "";

        if ($id > 0) {
            $post = Post::getOne($id);
            $oldFileName = $post->getPicture();
        } else {
            $post = new Post();
            $post->setAuthor($this->app->getAuth()->getLoggedUserName());
            date_default_timezone_set("Europe/Prague");
            $post->setDate(date("Y-m-d h:i:s"));
        }
        $post->setTitle($this->request()->getValue('title'));
        $post->setPicture($this->request()->getFiles()['picture']['name']);
        $post->setInfo($this->request()->getValue('info'));
        $post->setIngredients($this->request()->getValue('ingredients'));
        $post->setRecipe($this->request()->getValue('recipe'));

        $formErrors = $this->formErrors();
        if (count($formErrors) > 0) {
            if($id > 0) {
                return $this->html(
                    [
                        'post' => $post,
                        'errors' => $formErrors
                    ], 'edit'
                );
            }
            return $this->html(
                [
                    'post' => $post,
                    'errors' => $formErrors
                ], 'add'
            );
        } else {
            if ($oldFileName != "") {
                FileStorage::deleteFile($oldFileName);
            }
            $newFileName = FileStorage::saveFile($this->request()->getFiles()['picture']);
            $post->setPicture(FileStorage::UPLOAD_DIR . "/" . $newFileName);
            $post->save();
            return new RedirectResponse($this->url("user.posts"));
        }
    }

    public function delete()
    {
        $id = (int)$this->request()->getValue('id');
        $post = Post::getOne($id);
        $reviews = Review::getAll(whereClause: "post_id = '" . $post->getId() . "'");

        if (is_null($post)) {
            throw new HTTPException(404);
        } else {
            FileStorage::deleteFile($post->getPicture());
            foreach ($reviews as $review) {
                $review->delete();
            }
            $post->delete();
            return new RedirectResponse($this->url("user.posts"));
        }
    }

    private function formErrors(): array
    {
        $errors = [];
        if ($this->request()->getFiles()['picture']['name'] == "") {
            $errors[] = "Pole Súbor obrázka musí byť vyplnené!";
        }
        if ($this->request()->getValue('title') == "") {
            $errors[] = "Pole Názov príspevku musí byť vyplnené!";
        }
        if ($this->request()->getValue('recipe') == "") {
            $errors[] = "Pole Postup musí byť vyplnené!";
        }
        if ($this->request()->getFiles()['picture']['name'] != "" && !in_array($this->request()->getFiles()['picture']['type'], ['image/jpeg', 'image/png'])) {
            $errors[] = "Obrázok musí byť typu JPG alebo PNG!";
        }
        if ($this->request()->getValue('info') != "" && strlen($this->request()->getValue('info') > 200)) {
            $errors[] = "Pole Krátky popis jedla nesmie obsahovať viac ako 200 znakov!";
        }
        return $errors;
    }
}