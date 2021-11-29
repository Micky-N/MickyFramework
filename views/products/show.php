<h1>Produit : <?= $product->name ?></h1>

<p>Categorie : <?= $product->category->name ?></p>

<a class="btn btn-primary" href="<?= route('products.index') ?>"> <i class="fas fa-arrow-left"></i> Retour aux CRUD Produits</a>