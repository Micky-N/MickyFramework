<!DOCTYPE html>
<html lang="en_US">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= config('app_name') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.css">
    <link rel="stylesheet" href="./public/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/3.7.4/firebase.js"></script>
    <script>
        // Your web app's Firebase configuration
        const firebaseConfig = {
            apiKey: "AIzaSyBir9SJomBBQfvruGX5HWldMBj4MRg-xdY",
            authDomain: "mickyframework-23.firebaseapp.com",
            projectId: "mickyframework-23",
            storageBucket: "mickyframework-23.appspot.com",
            messagingSenderId: "1013072914840",
            appId: "1:1013072914840:web:1f5e1b38aa6f327b2123ed"
        };
        firebase.initializeApp(firebaseConfig);
    </script>
    <script src="./public/js/script.js"></script>
</head>

<body class="d-flex flex-column h-100">
    <header class="mb-5">
        <nav class="navbar navbar-expand-md navbar-light bg-light">
            <a class="navbar-brand" href="<?= route('home.index') ?>"><?= config('app_name') ?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item <?= currentRoute() == '/' ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= route('home.index') ?>">Accueil</a>
                    </li>
                    <li class="nav-item <?= namespaceRoute('categories') ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= route('categories.index') ?>">Categorie</a>
                    </li>
                    <li class="nav-item <?= namespaceRoute('products') ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= route('products.index') ?>">Produit</a>
                    </li>
                    <li class="nav-item <?= namespaceRoute('suppliers') ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= route('suppliers.index') ?>">Producteur</a>
                    </li>
                </ul>
                <ul class="navbar-nav mr-4">
                    <?php if(!auth()): ?>
                        <li class="mr-3">
                            <a class="btn btn-warning" href="<?= route('users.new') ?>">Inscription</a>
                        </li>
                        <li class="">
                            <a class="btn btn-primary" href="<?= route('auth.signin') ?>">Connexion</a>
                        </li>
                    <?php elseif(auth()): ?>
                        <li class="mr-3">
                            <a class="btn btn-success" href=""><?= auth()->fullName ?></a>
                        </li>
                        <li class="">
                            <a class="btn btn-info" href="<?= route('auth.logout') ?>">Déconnexion</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <main role="main" class="flex-shrink-0">
        <?php if (!empty($_GET['error'])) : ?>
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
</body>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.21/tableExport.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.21/libs/jsPDF/jspdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.21/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.19.1/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.19.1/dist/extensions/export/bootstrap-table-export.min.js"></script>

</html>