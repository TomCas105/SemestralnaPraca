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
                <h1 class="text-bold text-vertical-center text-32 text-center mb-4">Moje recepty</h1>
                <?php if (empty($data['posts'])) : ?>
                    <h5 class="text-center text-20 text-bold">Zatiaľ ste nezverejnili žiaden recept.</h5>
                <?php else: ?>
                    <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 g-3">
                        <?php require 'App/Views/post_list.view.php' ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col"></div>
</div>