<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Core\HTTPException;
use App\Core\Responses\JsonResponse;
use App\Core\Responses\RedirectResponse;
use App\Core\Responses\Response;
use App\Helpers\FileStorage;
use App\Models\Post;
use App\Models\Review;
use App\Models\SavedPost;
use Exception;

class PostController extends AControllerBase
{

    /**
     * @throws HTTPException
     * @throws Exception
     */
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
     * @throws Exception
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
                return true;
        }
    }

    public function add(): Response
    {
        return $this->html();
    }

    /**
     * @throws HTTPException
     * @throws Exception
     */
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

    /**
     * @throws HTTPException
     * @throws Exception
     */
    public function save(): Response
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
            $post->setDate(date("Y-m-d H:i:s"));
        }
        $post->setTitle($this->request()->getValue('title'));
        $post->setPicture($oldFileName);
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
            $newFile = $this->request()->getFiles()['picture'];
            $newFileName = $this->request()->getFiles()['picture']['name'];
            if ($newFileName != "") {
                if (FileStorage::UPLOAD_DIR . "/" . $newFileName != $oldFileName) {
                    FileStorage::deleteFile($oldFileName);
                    $saveFileName = FileStorage::saveFile($newFile);
                    $post->setPicture(FileStorage::UPLOAD_DIR . "/" . $saveFileName);
                }
            }
            $post->save();
            return new RedirectResponse($this->url("user.posts"));
        }
    }

    /**
     * @throws HTTPException
     * @throws Exception
     */
    public function delete(): Response
    {
        $id = (int)$this->request()->getValue('id');

        if (!$this->authorize("delete")) {
            return new RedirectResponse($this->url("home.posts"));
        }

        $post = Post::getOne($id);
        $reviews = Review::getAll(whereClause: "post_id = '" . $post->getId() . "'");

        if (!isset($post)) {
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

    /**
     * @throws HTTPException
     * @throws Exception
     */
    public function saveRecipe(): Response {

        $id = (int)$this->request()->getValue('id');
        $post = Post::getOne($id);

        if (!isset($post)) {
            throw new HTTPException(404);
        }

        $user = $this->app->getAuth()->getLoggedUserName();

        $savedPosts = SavedPost::getAll(whereClause: "save_author='" . $user . "' and post_id='" . $id . "'");
        if (empty($savedPosts)) {
            $savedPost = new SavedPost();
            $savedPost->setPostId($id);
            $savedPost->setSaveAuthor($user);
            $savedPost->save();
            return $this->json(array("ok" => 1));
        } else {
            foreach ($savedPosts as $savedPost) {
                $savedPost->delete();
            }
            return $this->json(array("ok" => 0));
        }
    }

    public function isSavedRecipe(): Response {

        $id = (int)$this->request()->getValue('id');
        $post = Post::getOne($id);

        if (!isset($post)) {
            throw new HTTPException(404);
        }

        $user = $this->app->getAuth()->getLoggedUserName();

        $savedPosts = SavedPost::getAll(whereClause: "save_author='" . $user . "' and post_id='" . $id . "'");
        if (empty($savedPosts)) {
            return $this->json(array("ok" => 0));
        } else {
            return $this->json(array("ok" => 1));
        }
    }

    /**
     * @throws Exception
     */
    private function formErrors(): array
    {
        $id = (int)$this->request()->getValue('id');
        $post = Post::getOne($id);
        $errors = [];

        $title = $this->request()->getValue('title');
        $recipe = $this->request()->getValue('recipe');
        $info = $this->request()->getValue('info');
        if ($this->request()->getFiles()['picture']['name'] == "" && $post->getPicture() == "") {
            $errors[] = "Obrázok nesmie byť prázdny!";
        }
        if ($this->request()->getFiles()['picture']['name'] != "" && !in_array($this->request()->getFiles()['picture']['type'], ['image/jpeg', 'image/png'])) {
            $errors[] = "Obrázok musí byť typu JPG alebo PNG!";
        }
        if ($title == "") {
            $errors[] = "Názov príspevku nesmie byť prázdny!";
        }
        if ($title != "" && strlen($title) > 100) {
            $errors[] = "Názov príspevku nesmie obsahovať viac ako 100 znakov! (" . strlen($title) . " znakov)";
        }
        if ($recipe == "") {
            $errors[] = "Pole príprava musí byť vyplnené!";
        }
        if ($info != "" && strlen($info) > 500) {
            $errors[] = "Krátky popis jedla nesmie obsahovať viac ako 500 znakov! (" . strlen($info) . " znakov)";
        }
        return $errors;
    }
}