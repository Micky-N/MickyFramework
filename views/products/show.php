<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 ml-md-2 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Produit</span>
                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Liste des fournisseurs
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <?php foreach ($product->suppliers as $supplier) : ?>
                        <button onclick="showProModal(this)" class="dropdown-item" data-pro="<?= $supplier->code_supplier ?>" data-toggle="modal" data-target="#prodModal">
                            <?= $supplier->name ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </h4>
            <ul class="list-group mb-3">
                <?php if ($product->photo) : ?>
                    <li class="list-group-item p-0 overflow-hidden">
                        <div>
                            <img id="photo" class="w-100 mx-auto p-0" alt="<?= $product->photo ?>" src="<?= $product->photo ?>">
                        </div>
                    </li>
                <?php endif; ?>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Nom</h6>
                        <small class="text-muted"><?= $product->name ?></small>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Vendeur</h6>
                        <small class="text-muted"><?= $product->seller ?></small>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Catégorie</h6>
                        <small class="text-muted"><?= $product->category->name ?></small>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Prix de vente</h6>
                        <small class="text-muted"><?= $product->getSelling_price() ?></small>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Quantité</h6>
                        <small class="text-muted"><?= $product->stock->quantity ?></small>
                    </div>
                </li>
            </ul>
        </div>

        <div class="col p-0">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Modifier le produit <?= $product->name ?></span>
                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#addModal"><i class="fa fa-plus"></i> Ajouter un fournisseur</button>
            </h4>
            <form id="update" method="post" action="<?= route('products.update', ['product' => $product->code_product]) ?>">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="name">Nom</label>
                        <input type="text" name="name" class="form-control" id="name" value="<?= $product->name ?>" required>
                    </div>
                    <div class="col form-group">
                        <label for="quantity">Quantité</label>
                        <input type="number" class="form-control" min="0" name="quantity" id="quantity" value="<?= $product->stock->quantity ?>">
                    </div>
                    <div class="col form-group">
                        <label for="selling_price">Prix</label>
                        <input type="number" step="0.01" class="form-control" name="selling_price" id="selling_price" value="<?= $product->selling_price ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col form-group">
                            <label for="photo">Photo</label>
                            <input type="text" name="photo" id="photo" class="form-control" value="<?= $product->photo ?>">
                        </div>
                        <div class="col form-group">
                            <label for="code_category">Catégorie</label>
                            <select class="form-control" name="code_category" id="code_category">
                                <?php foreach ($categories as $category) : ?>
                                    <option <?php if ($category->code_category == $product->code_category) : ?> selected <?php endif; ?> value="<?= $category->code_category ?>"><?= $category->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <button class="btn btn-block btn-success" type="submit">Modifier</button>
            </form>
        </div>
    </div>
</div>
<div id="prodModal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="uppro" action="" method="post" class="px-2 pt-4">
                <div class="d-flex justify-content-center">
                    <div class="row">
                        <div class="col-md-7 form-group">
                            <label for="">Fournisseur</label>
                            <input type="text" class="form-control" id="prodNom" value="" readonly>
                        </div>
                        <div class="col-md-5 form-group">
                            <label for="selling_price">Prix de production</label>
                            <input type="number" id="prodPrixProduction" step="0.01" min="0" class="form-control" name="purchase_price" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Modifier</button>
                    <a id="deletepro" href="" class="btn btn-danger" type="submit">Supprimer</a>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="addModal" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body py-0">
                <div class="col p-0">
                    <div class="modal-header">
                        <h4 class="d-flex justify-content-between align-items-center m-0">
                            <span class="text-muted">Ajouter un Fournisseur</span>
                        </h4>
                    </div>
                    <form id="addpro" action="<?= route('products.suppliers.create', ['product' => $product->code_product]) ?>" method="post" class="px-2 pt-4">
                        <div class="d-flex justify-content-center">
                            <div class="row">
                                <div class="col-md-7 form-group">
                                    <label for="code_supplier">Fournisseur</label>
                                    <select class="form-control" name="code_supplier" id="code_supplier">
                                        <option value="" selected>Aucun fournisseur séléctionné</option>
                                        <?php foreach ($suppliers as $supplier) : ?>
                                            <option value="<?= $supplier->code_supplier; ?>"><?= $supplier->name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-5 form-group">
                                    <label for="purchase_price">Prix de production</label>
                                    <input type="number" step="0.01" min="0" class="form-control" name="purchase_price" id="purchase_price">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-block btn-success" type="submit">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showProModal(e) {
        var product = JSON.parse('<?= json_encode($product->with('suppliers')); ?>')
        var suppliers = product.suppliers
        var supplier = suppliers.find(s => s.code_supplier == e.getAttribute('data-pro'))
        var action = `/products/${product.code_product}/suppliers/update/${supplier.code_supplier}`
        var href = `/products/${product.code_product}/suppliers/delete/${supplier.code_supplier}`
        $('form#uppro').attr('action', action)
        $('a#deletepro').attr('href', href)
        $('input#prodNom').attr('value', supplier.name)
        $('input#prodPrixProduction').attr('value', supplier.purchase_price)
    }
</script>