<div class="container">
    <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Catégorie</span>
            </h4>
            <ul class="list-group mb-3">
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Nom</h6>
                        <small class="text-muted"><?= $category->name ?></small>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Description</h6>
                        <small class="text-muted"><?= $category->description ?></small>
                    </div>
                </li>
            </ul>
        </div>
        <div class="col-md-8 order-md-1">
            <h4 class="mb-3">Modifier la catégorie</h4>
            <form class="needs-validation" action="<?= route('categories.update', ['category' => $category->code_category]) ?>" method="POST">
                <div class="row mb-3">
                    <div class="col">
                        <label for="name">Nom</label>
                        <input type="text" class="form-control" name="name" value="<?= $category->name ?>" required>
                    </div>
                    <div class="col">
                        <label for="description">Description</label>
                        <input type="text" class="form-control" name="description" value="<?= $category->description ?>" required>
                    </div>
                </div>
                <button class="btn btn-block btn-warning" type="submit">Modifier</button>
            </form>
        </div>
    </div>
</div>