<?php

/** @var Array $data */
/** @var \App\Models\Post $post */
/** @var App\Core\IAuthenticator $auth */
/** @var \App\Core\LinkGenerator $link */
?>

<div class="row">
    <div class="col"></div>
    <div id="mainPageContainer" class="col-lg-7 border">
        <span>
            <img class="recipe-preview shadow-small" src="<?= $data['post']->getPicture(); ?>" alt="">
            <p class="text-bold text-20 mt-3"><?= $data['post']->getTitle() ?></p>
            <?php if (!is_null($data['post']->getInfo())): ?>
                <p class="text-16"><?= $data['post']->getInfo() ?></p>
            <?php endif; ?>
            <?php if (!is_null($data['post']->getIngredients())): ?>
                <p class="text-16"><?= $data['post']->getIngredients() ?></p>
            <?php endif; ?>
            <?php if (!is_null($data['post']->getRecipe())): ?>
                <p class="text-16"><?= $data['post']->getRecipe() ?></p>
            <?php endif; ?>
        </span>
        <div class="m-2 d-flex gap-2 justify-content-end">
            <?php if ($auth->isLogged()): ?>
                <!--Úprava hodnotenia-->
                <?php if ($auth->getLoggedUserName() == $data['post']->getAuthor()): ?>
                    <a href="<?= $link->url('post.edit', ['id' => $data['post']->getId()]) ?>" class="btn border text-16 text-bold">Upraviť</a>
                    <a href="<?= $link->url('post.delete', ['id' => $data['post']->getId()]) ?>" class="btn border text-16 text-bold">Zmazať</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="col"></div>
</div>
