<div class="container">
    <div class="row">
        <div class="col-md-3 ml-md-2 order-md-2 mb-4">
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
                        <h6 class="my-0">Adresse</h6>
                        <small class="text-muted"><?= $supplier->address ?></small>
                    </div>
                </li>
            </ul>
        </div>

        <div class="col">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Modifier le fournisseur <?= $supplier->name ?></span>
            </h4>
            <form action="<?= route('suppliers.update', ['code_supplier' => $supplier->code_supplier]) ?>" method="post">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="name">Nom</label>
                        <input type="text" name="name" class="form-control" id="name" value="<?= $supplier->name ?>" required>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="num_street">Numero voie</label>
                        <input type="text" class="form-control" name="num_street" id="num_street" value="<?= $supplier->num_street ?>">
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="name_street">name de la voie</label>
                        <input type="text" class="form-control" name="name_street" value="<?= $supplier->name_street ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col form-group">
                            <label for="postcode">Code Postal</label>
                            <input type="text" class="form-control" name="postcode" id="postcode" value="<?= $supplier->postcode ?>">
                        </div>
                        <div class="col form-group">
                            <label for="city">Ville</label>
                            <input type="text" class="form-control" name="city" value="<?= $supplier->city ?>" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="informations">Informations</label>
                    <textarea row="1" class="form-control" name="informations" id="informations"><?= $supplier->informations ?>
            </textarea>
                </div>
                <button class="btn btn-block btn-success" type="submit">Modifier</button>
            </form>
        </div>
    </div>
</div>