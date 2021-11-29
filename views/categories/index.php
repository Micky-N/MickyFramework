<div class="col mb-5">
    <h1>Crud Categories</h1>
    <p>Liste :</p>
    <?php foreach ($categories as $categorie) : ?>
        <li><?= $categorie->code_category ?> : <?= $categorie->name ?></li>
        <form class="inline-block d-flex flex-row p-0" action="<?= route('categories.update', ['category' => $categorie->code_category]) ?>" method="post">
            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" name="name" id="nom" value="<?= $categorie->name ?>">
            </div>
            <div class="form-group mx-2">
                <label for="description">Description</label>
                <input type="text" name="description" id="description" value="<?= $categorie->description ?>">
            </div>
            <button type="submit" class="btn btn-sm py-0 px-3 m-0 btn-primary">Edit</button>
            <a href="<?= route('categories.delete', ['category' => $categorie->code_category]) ?>" class="btn btn-sm px-3 ml-2 btn-danger">Delete</a>
            <a href="<?= route('categories.show', ['category' => $categorie->code_category]) ?>" class="btn btn-sm px-3 ml-2 btn-success">Show</a>
        </form>
    <?php endforeach; ?>
</div>

<div class="col">
    <h2>Créer une categorie</h2>
    <form class="inline-block d-flex flex-row p-0" action="<?= route('categories.create') ?>" method="post">
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" name="name" id="nom" required>
        </div>
        <div class="form-group mx-2">
            <label for="description">Description</label>
            <input type="text" name="description" id="description">
        </div>
        <button type="submit" class="btn btn-sm py-0 px-3 m-0 btn-primary">Create</button>
    </form>
</div>