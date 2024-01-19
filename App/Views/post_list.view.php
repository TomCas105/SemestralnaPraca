<?php

/** @var Array $data */
/** @var \App\Models\Post $post */
/** @var \App\Core\LinkGenerator $link */
?>

<?php foreach ($data['posts'] as $post): ?>
    <div class="col">
        <a href="<?= $link->url('post.index', ['id' => $post->getId()]) ?>" class="card recipe-card shadow-medium">
            <div class="card-body">
                <img src="<?= $post->getPicture() ?>" alt="..." class="recipe-card-preview mb-2">
                <p class="mb-1 text-20 text-bold text-center text-vertical-center"><?= $post->getTitle() ?></p>
                <div class="rating-bar m-auto mb-3">
                                                    <span class="rating-bar-star-bg">
                                                        <span class="rating-bar-star"
                                                              style="width: <?= $post->getPostRating() * 20 ?>%"></span>
                                                    </span>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="d-flex gap-2">
                        <img src="App/Resources/images/icons/user_icon_01.png" alt="..."
                             class="rounded-circle user-icon">
                        <h6 class="mb-0 opacity-75"><?= $post->getAuthor() ?></h6>
                    </div>
                    <small class="opacity-50 text-nowrap"><?= $post->getFormattedAge() ?></small>
                </div>
            </div>
        </a>
    </div>
<?php endforeach; ?>
