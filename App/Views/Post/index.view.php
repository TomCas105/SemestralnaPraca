<?php

/** @var Array $data */
/** @var \App\Models\Post $post */
/** @var \App\Models\Review $review */
/** @var App\Core\IAuthenticator $auth */
/** @var \App\Core\LinkGenerator $link */
?>

<script>
    let post_id = "<?php print($data['post']->getId()) ?>";
    let user = "<?php print($auth->isLogged() ? $auth->getLoggedUserName() : "") ?>";
</script>
<script src="Public/js/script_postIndex.js"></script>

<div class="row">
    <div class="col"></div>
    <div id="mainPageContainer" class="col-lg-7 border">
        <img class="recipe-preview shadow-medium" src="<?= $data['post']->getPicture(); ?>" alt="">
        <div class="d-flex justify-content-between align-items-center mt-3 mb-1">
            <p class="text-bold text-20 text-vertical-center "><?= $data['post']->getTitle() ?></p>
            <div>
                <?php if ($auth->isLogged()): ?>
                    <div class="d-flex gap-2">
                        <?php if ($auth->getLoggedUserName() == "Admin"): ?>
                            <a id="recommend_recipe" class="btn border text-16 text-bold">Odporúčiť recept</a>
                        <?php endif; ?>
                        <a id="save_recipe" class="btn border text-16 text-bold">Uložiť recept</a>
                        <?php if ($auth->getLoggedUserName() == $data['post']->getAuthor()): ?>
                            <!--Úprava príspevku-->
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="<?= $link->url('post.edit', ['id' => $data['post']->getId()]) ?>"
                                   class="btn border text-16 text-bold">Upraviť</a>
                                <a href="<?= $link->url('post.delete', ['id' => $data['post']->getId()]) ?>"
                                   class="btn border text-16 text-bold">Zmazať</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="rating-bar mb-3">
                 <span class="rating-bar-star-bg">
                     <span class="rating-bar-star" style="width: <?= $data['post']->getPostRating() * 20 ?>%"></span>
                 </span>
        </div>

        <?php if ($data['post']->getInfo() != ""): ?>
            <p class="text-bold text-20 mb-0">Popis:</p>
            <p class="text-16"><?= $data['post']->getInfo() ?></p>
        <?php endif; ?>
        <?php if ($data['post']->getIngredients() != ""): ?>
            <p class="text-bold text-20 mb-0">Ingrediencie:</p>
            <p class="text-16"><?= $data['post']->getIngredients() ?></p>
        <?php endif; ?>
        <?php if ($data['post']->getRecipe() != ""): ?>
            <p class="text-bold text-20 mb-0">Príprava:</p>
            <p class="text-16"><?= $data['post']->getRecipe() ?></p>
        <?php endif; ?>
        <?php if ($auth->isLogged() && $auth->getLoggedUserName() != $data['post']->getAuthor()): ?>
            <!--Hodnotenie-->
            <div class="justify-content-between">
                <div id="alert_container" style="display: none" >
                </div>
                <div>
                    <p class="text-bold text-20 text-center mt-3 mb-1">Hodnotenie</p>
                    <div class="d-flex mb-3 justify-content-center" onload="refreshRating(0)">
                        <a id="rating1" class="rating-star"></a>
                        <a id="rating2" class="rating-star"></a>
                        <a id="rating3" class="rating-star"></a>
                        <a id="rating4" class="rating-star"></a>
                        <a id="rating5" class="rating-star"></a>
                    </div>
                </div>
                <div class="input-group mb-3 ">
                    <input type="hidden" name="review_rating" id="review_rating" value="0">
                    <textarea class="form-control no-resize" aria-label="With textarea" name="review_text"
                              style="height: 150px"
                              id="review_text"><?= @$data['review_text'] ?></textarea>
                </div>
                <button id="update_review" class="btn border text-bold text-16">Hodnotiť</button>
                <button id="delete_review" class="btn border text-bold text-16">Odstrániť</button>
            </div>
        <?php endif; ?>

        <div id="review_container" style="display: none">
            <p class="text-bold text-20 text-center mt-3">Recenzie:</p>
            <div class="d-flex flex-column flex-md-row p-3 gap-3 py-md-3 align-items-center justify-content-center ">
                <div id="reviewList" class="list-group shadow-small justify-content-between">

                </div>
            </div>
        </div>
    </div>
    <div class="col"></div>
</div>

