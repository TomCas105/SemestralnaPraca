<?php

/** @var Array $data */
/** @var \App\Models\Post $post */
/** @var \App\Core\LinkGenerator $link */
?>

<div class="row">
    <div class="col"></div>
    <div id="mainPageContainer" class="col-lg-8 border">
        <div class="album py-3">
            <div class="container">
                <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 g-3">
                    <?php foreach ($data['posts'] as $post): ?>

                        <div class="col ">
                            <a href="<?= $link->url('post.index', ['id' => $post->getId()]) ?>" class="card recipe-card">
                                <div class="card-body h-100">
                                    <img src="<?= $post->getPicture() ?>" alt="..." class="recipe-card-preview">
                                    <div class="d-flex h-100 gap-2 py-3">
                                        <div class="d-flex me-auto gap-2 justify-content-between">
                                            <div>
                                                <p class="mb-1 opacity-75"><?= $post->getTitle() ?></p>
                                                <div class="rating-bar">
                                                    <span class="rating-bg">
                                                        <span class="rating-star"
                                                              style="width: <?= $post->getPostRating() * 20 ?>%"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <small class="opacity-50 text-nowrap"><?= $post->getFormattedAge() ?></small>
                                    </div>
                                </div>
                            </a>
                        </div>

                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col"></div>
</div>