<div class="col mb-5">
    <h1>Crud supplier</h1>
    <p>Liste :</p>
    <?php foreach ($suppliers as $supplier) : ?>
        <li><?= $supplier->code_supplier ?> : <?= $supplier->name ?></li>
        <form class="inline-block d-flex flex-row p-0" action="<?= route('suppliers.update', ['supplier' => $supplier->code_supplier]) ?>" method="post">
            <div class="form-group col-sm">
                <label for="name">Nom</label>
                <input type="text" name="name" id="nom" value="<?= $supplier->name ?>"> </br>
                <label for="informations">Informations</label>
                <input type="text" size="30" name="informations" id="informations" value="<?= $supplier->informations ?>">
            </div>
            <div class="form-group col-sm">
                <label for="address">Adresse</label>
                <input size="3" type="text" name="num_street" id="num_street" value="<?= $supplier->num_street ?>">
                <input type="text" name="name_street" id="name_street" value="<?= $supplier->name_street ?>"></br>
                <input type="text" name="postcode" id="postcode" value="<?= $supplier->postcode ?>">
                <input size="3" type="text" name="city" id="city" value="<?= $supplier->city ?>">
            </div>
            <button type="submit" class="btn btn-sm py-0 px-3 m-0 btn-primary">Edit</button>
            <a href="<?= route('suppliers.delete', ['supplier' => $supplier->code_supplier]) ?>" class="btn btn-sm px-3 ml-2 btn-danger">Delete</a>
            <a href="<?= route('suppliers.show', ['supplier' => $supplier->code_supplier]) ?>" class="btn btn-sm px-3 ml-2 btn-success">Show</a>
        </form>
    <?php endforeach; ?>
</div>

<div class="col">
    <h2>Créer un supplier</h2>
    <form class="inline-block d-flex flex-row p-0" action="<?= route('suppliers.create') ?>" method="post">
        <div class="col-sm">
            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="informations">Informations</label>
                <input size="30" type="text" name="informations" id="informations">
            </div>
            <div class="form-group">
                <label for="num_street">Numero voie</label>
                <input size="4" type="text" name="num_street" id="num_street">
            </div>
        </div>
        <div class="col-sm">
            <div class="form-group">
                <label for="name_street">Nom voie</label>
                <input type="text" name="name_street" id="name_street">
            </div>
            <div class="form-group">
                <label for="postcode">Code Postal</label>
                <input size="3" type="text" name="postcode" id="postcode">
            </div>
            <div  class="form-group">
                <label for="city">Ville</label>
                <input type="text" name="city" id="city">
            </div>
        </div>
        <button type="submit" class="btn btn-sm py-0 px-3 m-0 btn-primary">Create</button>
    </form>
</div>