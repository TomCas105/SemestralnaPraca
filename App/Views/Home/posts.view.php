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
                    <?php if (empty($data['posts'])) : ?>
                        <h5>Nenašli sa žiadne recepty.</h5>
                    <?php else: ?>
                        <?php require 'App/Views/post_list.view.php' ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col"></div>
</div>