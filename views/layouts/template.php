<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TP-Bootstrap</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.css">
    <link rel="stylesheet" href="../../public/css/style.css">
</head>

<body class="d-flex flex-column h-100">
    <header class="mb-5">
        <nav class="navbar navbar-expand-md navbar-dark bg-primary">
            <a class="navbar-brand" href="?action=index">TPBOOTSTRAP</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <?php foreach ([
                        'Accueil' => 'index',
                        'Categories' => 'categories'
                    ] as $titre => $href) : ?>
                        <li class="nav-item <?= strpos($_GET['action'], $href) === 0 ? 'active' : '' ?>">
                            <a class="nav-link" href="?action=<?= $href ?>"><?= $titre ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </nav>
    </header>
    <main role="main" class="flex-shrink-0">
        <?php if(!empty($_GET['error'])): ?>
            <div class="alert alert-danger"><?= $_GET['error'] ?></div>
        <?php endif; ?>
        <div class="container">
            <div class="container my-5">
                <!-- Toutes les vues seront dans cette variable -->
                <?= $content ?>
            </div>
        </div>
    </main>
    <footer class="footer bg-light mt-auto py-3">
        <div class="container">
            <span class="text-muted">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Cupiditate debitis sint dolores, facere nisi fuga explicabo similique modi ea inventore qui ipsa autem minus, ab non, mollitia dolorem cumque repellat.</span>
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.js"></script>
    <script src="../../public/js/script.js"></script>
</body>

</html>