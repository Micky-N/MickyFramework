<!DOCTYPE html>
<html lang="en_US">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERREUR</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="stylesheet" href="./public/assets/css/style.css">
    <style>
        #main {
            height: 100vh;
        }
        #mky_error {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 35rem;
            z-index: 0;
            margin: 0;
            padding: 0;
            opacity: 0.4;
        }
        .error_div {
            z-index: 10;
        }
        .error_div h1 {
            font-size: 5rem;
        }
        .error_div h2{
            font-size: 3rem;
        }
    </style>
</head>

<body class="bg-dark d-flex align-items-center justify-content-center overflow-hidden">
<h1 id="mky_error" class="d-flex justify-content-center align-items-center">MKY</h1>
<main id="main" class="d-flex align-items-center">
    <?= content('body') ?>
</main>
</body>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js"></script>
</html>