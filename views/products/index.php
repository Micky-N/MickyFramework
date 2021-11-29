<div class="col mb-5">
    <h1>Crud products</h1>
    <p>Liste :</p>
    <?php foreach ($products as $product) : ?>
        <li><?= $product->code_product ?> : <?= $product->name ?></li>
        <form class="inline-block d-flex flex-row p-0" action="<?= route('product.update', ['products' => $product->code_product]) ?>" method="post">
            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" name="name" id="name" value="<?= $product->name ?>">
            </div>
            <div class="form-group mx-2">
                <label for="description">Photo</label>
                <input type="text" name="description" id="description" value="Photo indisponible"> <!-- Photo a ajouter plus tard cf: Issues #2 -->
            </div>
            <div class="form-group mx-2">
                <label for="categorie">Categorie</label>
                <p><?= $product->category->name ?></p>
            </div>
            <button type="submit" class="btn btn-sm py-0 px-3 m-0 btn-primary">Edit</button>
            <a href="<?= route('products.delete', ['product' => $product->code_product]) ?>" class="btn btn-sm px-3 ml-2 btn-danger">Delete</a>
            <a href="<?= route('products.show', ['product' => $product->code_product]) ?>" class="btn btn-sm px-3 ml-2 btn-success">Show</a>
        </form>
    <?php endforeach; ?>
</div>

<div class="col">
    <h2>Créer un product</h2>
    <form class="inline-block d-flex flex-row p-0" action="<?= route('products.create') ?>" method="post">
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div class="form-group mx-2">
            <label for="photo">Lien Photo</label>
            <input type="text" name="photo" id="photo">
        </div>
        <div class="form-group mx-2">

            <p>Categorie<br>
                <select name="code_category" id="code_category">
                    <option value="" selected>Aucune catégorie selectionner </option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?= $category->code_categorie ?> "> <?= $category->name ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
        </div>
        <button type="submit" class="btn btn-sm py-0 px-3 m-0 btn-primary">Create</button>
    </form>
</div>