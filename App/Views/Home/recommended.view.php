<?php

/** @var Array $data */
/** @var \App\Models\Post $post */
/** @var \App\Core\LinkGenerator $link */
?>

<div class="row">
    <div class="col"></div>
    <div id="mainPageContainer" class="col-lg-8 border">
        <div class="album py-3">
            <div class="container  g-3  ">
                <h1 class="text-bold text-vertical-center text-32 text-center mb-4">Odporúčané</h1>
                <?php if (empty($data['posts'])) : ?>
                    <h5>Momentálne nie sú odporúčané žiadne recepty.</h5>
                <?php else: ?>
                    <?php foreach ($data['posts'] as $post): ?>
                        <div class="row px-4">
                            <a href="<?= $link->url('post.index', ['id' => $post->getId()]) ?>"
                               class="card recipe-card">
                                <div class="card-body">
                                    <div class="container p-0 py-3">
                                        <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2">
                                            <div class="col">
                                                <img src="<?= $post->getPicture() ?>" alt="..."
                                                     class="recipe-card-preview ms-0">
                                            </div>
                                            <div class="col">
                                                <div class="d-flex gap-2 py-3">
                                                    <img src="App/Resources/images/icons/user_icon_01.png" alt="..."
                                                         class="rounded-circle user-icon">
                                                    <div class="d-flex me-auto gap-2 justify-content-between">
                                                        <div>
                                                            <h6 class="mb-0"><?= $post->getAuthor() ?></h6>
                                                            <p class="mb-1 opacity-75"><?= $post->getTitle() ?></p>
                                                            <div class="rating-bar">
                                                            <span class="rating-bar-star-bg">
                                                                <span class="rating-bar-star"
                                                                      style="width: <?= $post->getPostRating() * 20 ?>%"></span>
                                                            </span>
                                                            </div>

                                                            <?php if (!empty($post->getInfo())): ?>
                                                                <div class="mt-3 mb-1">
                                                                    <p class="opacity-75"><?= $post->getInfo() ?></p>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <small class="opacity-50 text-nowrap"><?= $post->getFormattedAge() ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col"></div>
</div>