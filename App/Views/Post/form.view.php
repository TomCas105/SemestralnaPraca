<?php

/** @var Array $data */
/** @var \App\Models\Post $post */
/** @var \App\Core\LinkGenerator $link */
?>


<?php if (!is_null(@$data['errors'])): ?>
    <?php foreach ($data['errors'] as $error): ?>
        <div class="alert alert-danger" role="alert">
            <?= $error ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<form method="post" action="<?= $link->url('post.save') ?>" enctype="multipart/form-data">

    <input type="hidden" name="id" value="<?= @$data['post']?->getId() ?>">

    <div class="container p-0">

        <div class="row">
            <label for="inputGroupFile02" class="form-label">Náhľadový obrázok</label>
            <img class="recipe-preview mb-2" id="img-preview" src="<?= @$data['post']?->getPicture() ?>" alt="..."/>
            <div class="input-group mb-3 has-validation">
                <input type="file" class="form-control " name="picture" id="inputGroupFile02"
                       onchange="loadFile(event)">
            </div>
            <script>
                var loadFile = function (event) {
                    var reader = new FileReader();
                    reader.onload = function () {
                        var output = document.getElementById('img-preview');
                        output.src = reader.result;
                    };
                    reader.readAsDataURL(event.target.files[0]);
                };
            </script>
        </div>

        <div class="row">
            <label for="post-title" class="form-label">Názov receptu</label>
            <div class="input-group has-validation mb-3 ">
        <textarea class="form-control no-resize" aria-label="With textarea" name="title" style="height: 25px"
                  id="post-title"><?= @$data['post']?->getTitle() ?></textarea>
            </div>
        </div>

        <div class="row">
            <label for="post-info" class="form-label">Krátky popis jedla</label>
            <div class="input-group has-validation mb-3 ">
        <textarea class="form-control no-resize" aria-label="With textarea" name="info" style="height: 100px"
                  id="post-info"><?= @$data['post']?->getInfo() ?></textarea>
            </div>
        </div>

        <div class="row">
            <label for="post-ingredients" class="form-label">Ingrediencie</label>
            <div class="input-group has-validation mb-3 ">
        <textarea class="form-control no-resize" aria-label="With textarea" name="ingredients" style="height: 250px"
                  id="post-ingredients"><?= @$data['post']?->getIngredients() ?></textarea>
            </div>
        </div>

        <div class="row">
            <label for="post-recipe" class="form-label">Postup</label>
            <div class="input-group has-validation mb-3 ">
        <textarea class="form-control no-resize" aria-label="With textarea" name="recipe" style="height: 350px"
                  id="post-recipe"><?= @$data['post']?->getRecipe() ?></textarea>
            </div>
        </div>
    </div>
    <button type="submit" class="btn border text-bold text-16">Uložiť</button>
</form>
