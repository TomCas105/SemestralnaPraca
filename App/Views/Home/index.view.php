<?php

/** @var Array $data */
/** @var \App\Models\Post $post */
/** @var \App\Core\LinkGenerator $link */
?>

<script src="Public/js/script_homeIndex.js" type="module"></script>

<div class="row">
    <div class="col"></div>
    <div id="mainPageContainer" class="col-lg-7 border">
        <div id="mainPageCarousel" class="carousel shadow-medium slide mb-3" data-bs-ride="carousel">
            <div class="carousel-inner">

                <?php $first = true;
                foreach ($data['topFive'] as $post): ?>
                    <div class="carousel-item <?= $first ? "active" : "" ?>">
                        <a href="<?= $link->url('post.index', ['id' => $post->getId()]) ?>">
                            <img src="<?= $post->getPicture() ?>" class="recipe-preview"
                                 alt="...">
                            <div class="carousel-caption carousel-recipe-caption">
                                <img src="App/Resources/images/icons/user_icon_01.png" alt="..."
                                     class="rounded-circle user-icon">
                                <div class="d-flex gap-2 me-1 justify-content-between">
                                    <div>
                                        <h6 class="mb-0"><?= $post->getAuthor() ?></h6>
                                        <p class="mb-1 opacity-75"><?= $post->getTitle() ?></p>
                                        <div class="rating-bar">
                                        <span class="rating-bar-star-bg">
                                            <span class="rating-bar-star"
                                                  style="width: <?= $post->getPostRating() * 20 ?>%"></span>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php $first = false; ?>
                <?php endforeach; ?>

            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#mainPageCarousel"
                    data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainPageCarousel"
                    data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>

        <div class="d-flex flex-column flex-md-row p-3 gap-3 py-md-3 align-items-center justify-content-center ">
            <div id="reviewList" class="list-group shadow-small justify-content-between">

            </div>
        </div>
    </div>
    <div class="col"></div>
</div>
