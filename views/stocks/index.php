<div class="col mb-5">
    <h1>Crud stock</h1>
    <p>Liste :</p>
    <?php foreach ($stocks as $stock) : ?>
        <li><?= $stock->code_stock ?> : <?= $stock->name ?></li>
        <form class="inline-block d-flex flex-row p-0" action="<?= route('stocks.update', ['stock' => $stock->code_stock]) ?>" method="post">
            <div class="form-group col-sm">
                <label for="name">Nom</label>
                <input type="text" name="name" id="nom" value="<?= $stock->name ?>">
            </div>
            <div class="form-group col-sm">
                <label for="quantity">Quantités</label>
                <input type="number" name="quantity" id="quantity" value="100">
            </div>
            <button type="submit" class="btn btn-sm py-0 px-3 m-0 btn-primary">Edit</button>
            <a href="<?= route('stocks.delete', ['stock' => $stock->code_stock]) ?>" class="btn btn-sm px-3 ml-2 btn-danger">Delete</a>
            <a href="<?= route('stocks.show', ['stock' => $stock->code_stock]) ?>" class="btn btn-sm px-3 ml-2 btn-success">Show</a>
        </form>
    <?php endforeach; ?>
</div>

<div class="col">
    <h2>Créer un stock</h2>
    <form class="inline-block d-flex flex-row p-0" action="<?= route('stocks.create') ?>" method="post">
        <div class="col-sm">
            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" name="name" id="name" required>
            </div>
        </div>
        <button type="submit" class="btn btn-sm py-0 px-3 m-0 btn-primary">Create</button>
    </form>
</div>