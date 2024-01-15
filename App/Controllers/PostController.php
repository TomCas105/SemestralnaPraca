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

    /**
     * @throws \Exception
     */
    public function authorize($action): bool
    {
        switch ($action) {
            case 'delete':
            case 'edit' :
                $id = (int)$this->request()->getValue("id");
                $postToCheck = Post::getOne($id);
                return $postToCheck->getAuthor() == $this->app->getAuth()->getLoggedUserName();
            case 'save':
                $id = (int)$this->request()->getValue("id");
                if ($id > 0) {
                    $postToCheck = Post::getOne($id);
                    return $postToCheck->getAuthor() == $this->app->getAuth()->getLoggedUserName();
                } else {
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

        if (!$this->authorize("edit")) {
            return new RedirectResponse($this->url("home.posts"));
        }

        return $this->html(['post' => $post]);
    }

    public function save()
    {
        $id = (int)$this->request()->getValue('id');

        if (!$this->authorize("save")) {
            return new RedirectResponse($this->url("home.posts"));
        }

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
            if ($id > 0) {
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
            $newFileName = $this->request()->getFiles()['picture'];
            //ulozenie nového suboru ak starý je prázdny alebo ak sa nerovná so starému
            if (!is_null($newFileName) && $newFileName != "" && (is_null($oldFileName) || ($oldFileName != $newFileName))) {
                if (!is_null($oldFileName)) {
                    FileStorage::deleteFile($oldFileName);
                }
                $saveFileName = FileStorage::saveFile($newFileName);
                $post->setPicture(FileStorage::UPLOAD_DIR . "/" . $saveFileName);
            }
            $post->save();
            return new RedirectResponse($this->url("user.posts"));
        }
    }

    public function delete()
    {
        $id = (int)$this->request()->getValue('id');

        if (!$this->authorize("delete")) {
            return new RedirectResponse($this->url("home.posts"));
        }

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
        $id = (int)$this->request()->getValue('id');
        $post = Post::getOne($id);
        $errors = [];
        if ($this->request()->getFiles()['picture']['name'] == "" && is_null($post->getPicture())) {
            $errors[] = "Obrázok nesmie byť prázdny!";
        }
        if ($this->request()->getValue('title') == "") {
            $errors[] = "Názov príspevku nesmie byť prázdny!";
        }
        if ($this->request()->getValue('recipe') == "") {
            $errors[] = "Pole príprava musí byť vyplnené!";
        }
        if ($this->request()->getFiles()['picture']['name'] != "" && !in_array($this->request()->getFiles()['picture']['type'], ['image/jpeg', 'image/png'])) {
            $errors[] = "Obrázok musí byť typu JPG alebo PNG!";
        }
        if ($this->request()->getValue('info') != "" && strlen($this->request()->getValue('info')) > 200) {
            $errors[] = "Krátky popis jedla nesmie obsahovať viac ako 200 znakov!" . strlen($this->request()->getValue('info'));
        }
        return $errors;
    }
}