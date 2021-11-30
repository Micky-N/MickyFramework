<div class="container">
    <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Produit</span>
            </h4>
            <ul class="list-group mb-3">
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Nom</h6>
                        <small class="text-muted"><?= $product->name ?></small>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Lien photo</h6>
                        <small class="text-muted"><?= $product->photo ?></small>
                    </div>
                </li>
            </ul>
        </div>
        <div class="col-md-8 order-md-1">
            <h4 class="mb-3">Modifier le produit</h4>
            <form class="needs-validation" action="<?= route('products.update', ['product' => $product->code_product]) ?>" method="POST">
                <div class="row mb-3">
                    <div class="col">
                        <label for="name">Nom</label>
                        <input type="text" class="form-control" name="name" value="<?= $product->name ?>" required>
                    </div>
                    <div class="col">
                        <label for="photo">Lien photo</label>
                        <input type="text" class="form-control" name="photo" value="<?= $product->photo ?>" required>
                    </div>
                </div>
                <button class="btn btn-block btn-primary" type="submit">Modifier</button>
            </form>
        </div>
    </div>
</div>