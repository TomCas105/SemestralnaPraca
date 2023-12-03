<?php

/** @var string $contentHTML */
/** @var \App\Core\IAuthenticator $auth */
/** @var \App\Core\LinkGenerator $link */
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <title><?= \App\Config\Configuration::APP_NAME ?></title>
    <link rel="icon" type="image/x-icon" href="App/Resources/images/icons/web_icon.png">

    <!-- Bootstrap -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
    <!-- Bootstrap -->

    <!-- Bootstrap ikony -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Bootstrap ikony -->

    <link rel="stylesheet" href="App/Resources/css/style.css">
    <script src="App/Resources/js/script.js"></script>
</head>
<body>
<header class="border-bottom border-5">
    <div id="navbarContainer" class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="<?= $link->url("home.index") ?>"
               class="d-flex align-items-center mb-2 me-0 me-lg-2 mb-lg-0 link-body-emphasis text-decoration-none">
                <img src="App/Resources/images/icons/web_icon.png" class="rounded-circle web-icon"
                     title="Návrat na hlavnú stránku"
                     alt="<?= \App\Config\Configuration::APP_NAME ?>">
            </a>


            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 mb-lg-0 justify-content-center">
                <li>
                    <a href="<?= $link->url("home.posts") ?>" class="nav-link px-2 link-body-emphasis">Recepty</a>
                </li>
                <li>
                    <a href="<?= $link->url("home.weeks_choice") ?>" class="nav-link px-2 link-body-emphasis">Výber
                        týždňa</a>
                </li>
            </ul>

            <form class="col-12 col-lg-auto mb-2 mb-lg-0 me-lg-2" role="search">
                <input type="search" class="form-control" placeholder="Vyhľadať..." aria-label="Search">
            </form>

            <?php if ($auth->isLogged()): ?>
                <div class="dropdown text-end">
                    <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="App/Resources/images/icons/user_icon_01.png" alt="..."
                             class="rounded-circle user-icon">
                    </a>
                    <ul class="dropdown-menu text-small">
                        <li><a class="dropdown-item" href="#" style="font-weight: bold"><?= $auth->getLoggedUserName() ?></a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="<?= $link->url("post.add") ?>">Pridať recept</a></li>
                        <li><a class="dropdown-item" href="<?= $link->url("user.posts") ?>">Moje recepty</a></li>
                        <li><a class="dropdown-item" href="#">Uložené recepty</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="<?= $link->url("auth.logout") ?>">Odhlásiť sa</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link px-2 link-body-emphasis" href="<?= \App\Config\Configuration::LOGIN_URL ?>">Prihlásenie</a>
                    </li>
                </ul>
            <?php endif; ?>

        </div>
    </div>
</header>

<div class="container-fluid mt-3">
    <div class="web-content">
        <?= $contentHTML ?>
    </div>
</div>

</body>
</html>
