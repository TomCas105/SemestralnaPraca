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
            <p class="text-bold text-20 mt-3 mb-1"><?= $data['post']->getTitle() ?></p>

             <div class="rating-bar mb-3">
                 <span class="rating-bar-star-bg">
                     <span class="rating-bar-star" style="width: <?= $data['post']->getPostRating() * 20 ?>%"></span>
                 </span>
             </div>

            <?php if (!is_null($data['post']->getInfo())): ?>
                <p class="text-bold text-20 mb-0">Popis:</p>
                <p class="text-16"><?= $data['post']->getInfo() ?></p>
            <?php endif; ?>
            <?php if (!is_null($data['post']->getIngredients())): ?>
                <p class="text-bold text-20 mb-0">Ingrediencie:</p>
                <p class="text-16"><?= $data['post']->getIngredients() ?></p>
            <?php endif; ?>
            <?php if (!is_null($data['post']->getRecipe())): ?>
                <p class="text-bold text-20 mb-0">Príprava:</p>
                <p class="text-16"><?= $data['post']->getRecipe() ?></p>
            <?php endif; ?>
        </span>
        <?php if ($auth->isLogged()): ?>
            <?php if ($auth->getLoggedUserName() == $data['post']->getAuthor()): ?>
                <!--Úprava príspevku-->
                <div class="m-2 d-flex gap-2 justify-content-end">
                    <a href="<?= $link->url('post.edit', ['id' => $data['post']->getId()]) ?>"
                       class="btn border text-16 text-bold">Upraviť</a>
                    <a href="<?= $link->url('post.delete', ['id' => $data['post']->getId()]) ?>"
                       class="btn border text-16 text-bold">Zmazať</a>
                </div>
            <?php else: ?>
                <!--Hodnotenie-->
                <div class="m-2 d-flex gap-2 justify-content-center">
                    <span id="rating1" class="rating-star rating-star-checked"></span>
                    <span id="rating2" class="rating-star rating-star-checked"></span>
                    <span id="rating3" class="rating-star rating-star-checked"></span>
                    <span id="rating4" class="rating-star"></span>
                    <span id="rating5" class="rating-star"></span>
                </div>
                <script>

                </script>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <div class="col"></div>
</div>
