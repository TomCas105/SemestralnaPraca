<?php

$layout = 'auth';
/** @var Array $data */
/** @var \App\Core\LinkGenerator $link */
?>

<div class="container">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card card-signin m-5">
                <div class="card-body text-center">
                    <h5 class="text-20 text-bold">Vitajte!</h5>
                    <small>Pre pokračovanie sa zaregistrujte</small>
                    <div class="text-center text-danger mb-3">
                        <?= @$data['message'] ?>
                    </div>
                    <form class="form-signin" method="post" action="<?= $link->url("signup") ?>">
                        <div class="form-label-group mb-3">
                            <input name="login" type="text" id="login" class="form-control" placeholder="Login"
                                   required autofocus>
                        </div>

                        <div class="form-label-group mb-3">
                            <input name="password" type="password" id="password" class="form-control"
                                   placeholder="Password" required>
                        </div>
                        <div>
                            <button class="btn border text-bold text-16" type="submit" name="submit">Zaregistrovať
                            </button>
                        </div>
                    </form>
                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <p>Ste zaregistrovaný?</p>
                        <a href="<?= $link->url("login") ?>">Prihlásiť</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>