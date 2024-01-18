<?php

/** @var Array $data */
/** @var \App\Models\Post $post */
/** @var \App\Models\Review $review */
/** @var App\Core\IAuthenticator $auth */
/** @var \App\Core\LinkGenerator $link */
?>


<div class="row">
    <div class="col"></div>
    <div id="mainPageContainer" class="col-lg-7 border">
        <img class="recipe-preview shadow-medium" src="<?= $data['post']->getPicture(); ?>" alt="">
        <div class="d-flex justify-content-between align-items-center mt-3 mb-1">
                <p class="text-bold text-20 text-vertical-center "><?= $data['post']->getTitle() ?></p>
                <div>
                    <?php if ($auth->isLogged()): ?>
                        <div class="d-flex gap-2">
                        <a id="saveRecipeButton" class="btn border text-16 text-bold">Uložiť recept</a>
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
                <?php if (!is_null(@$data['errors'])): ?>
                    <?php foreach ($data['errors'] as $error): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $error ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <div>
                    <p class="text-bold text-20 text-center mt-3 mb-1">Hodnotenie</p>
                    <div class="d-flex mb-3 justify-content-center" onload="refreshRating(0)">
                        <a onclick="setRating(1)" class="rating-star"></a>
                        <a onclick="setRating(2)" class="rating-star"></a>
                        <a onclick="setRating(3)" class="rating-star"></a>
                        <a onclick="setRating(4)" class="rating-star"></a>
                        <a onclick="setRating(5)" class="rating-star"></a>
                    </div>
                </div>
                <form name="reviewForm" method="post"
                      action="<?= $link->url('post.review', ['id' => $data['post']->getId()]) ?>"
                      enctype="multipart/form-data">
                    <div class="input-group mb-3 ">
                        <input type="hidden" name="review_rating" id="review_rating" value="0">
                        <textarea class="form-control no-resize" aria-label="With textarea" name="review_text"
                                  style="height: 100px"
                                  id="post-info"><?= @$data['review_text'] ?></textarea>
                    </div>
                    <button type="submit" class="btn border text-bold text-16">Hodnotiť</button>
                </form>
            </div>
        <?php endif; ?>

        <?php if (!empty($data['post']->getReviews())): ?>
            <div class="flex-column flex-md-row p-3 gap-3 py-md-3 align-items-center justify-content-between ">
                <p class="text-bold text-20 text-center mt-3">Recenzie:</p>
                <div class="list-group shadow-small justify-content-between">
                    <?php foreach ($data['post']->getReviews() as $review): ?>
                        <div class="list-group-item d-flex gap-3 py-3">
                            <img src="App/Resources/images/icons/user_icon_01.png" alt="..."
                                 class="rounded-circle user-icon">
                            <div class="d-flex w-100 justify-content-between">
                                <div class="gap-2">
                                    <h6 class="mb-1"><?= $review->getReviewAuthor() ?></h6>
                                    <div class="rating-bar">
                                        <span class="rating-bar-star-bg">
                                            <span class="rating-bar-star"
                                                  style="width: <?= $review->getRating() * 20 ?>%"></span>
                                        </span>
                                    </div>
                                    <?php if ($review->getReviewText() != ""): ?>
                                        <small><?= $review->getReviewText() ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="col"></div>
</div>

<script>var post_id = "<?php print($data['post']->getId()) ?>"; </script>
<script>var user = "<?php print($auth->getLoggedUserName()) ?>"; </script>
<script src="App/Resources/js/script_review.js"></script>
