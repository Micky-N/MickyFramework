<div class="container">
    <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Fournisseur</span>
            </h4>
            <ul class="list-group mb-3">
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Nom</h6>
                        <small class="text-muted"><?= $supplier->name ?></small>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Information</h6>
                        <small class="text-muted"><?= $supplier->informations ?></small>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">adresse</h6>
                        <small class="text-muted"><?= $supplier->address ?></small>
                    </div>
                </li>
            </ul>
        </div>
        <div class="col-md-8 order-md-1">
            <h4 class="mb-3">Modifier le fournisseur</h4>
            <form class="needs-validation" action="<?= route('suppliers.update', ['supplier' => $supplier->code_supplier]) ?>" method="POST">
                <div class="row">
                    <div class="col mb-3">
                        <label for="name">Nom</label>
                        <input type="text" class="form-control" name="name" value="<?= $supplier->name ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="num_street">Numéro de la voie</label>
                            <input type="text" class="form-control" name="num_street" value="<?= $supplier->num_street ?>" required>
                        </div>
                        <div class="col-md-9">
                            <label for="name_street">Nom de la voie</label>
                            <input type="text" class="form-control" name="name_street" value="<?= $supplier->name_street ?>" required>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label for="postcode">Code postal</label>
                            <input type="text" class="form-control" name="postcode" value="<?= $supplier->postcode ?>" required>
                        </div>
                        <div class="col">
                            <label for="city">Ville</label>
                            <input type="text" class="form-control" name="city" value="<?= $supplier->city ?>" required>
                        </div>
                    </div>
                </div>
                <button class="btn btn-block btn-success" type="submit">Modifier</button>
            </form>
        </div>
    </div>
</div>