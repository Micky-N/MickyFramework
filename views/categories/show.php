<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 ml-md-2 order-md-2 mb-4">
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

        <div class="col p-0">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Modifier la catégorie <?= $category->name ?></span>
            </h4>
            <form action="<?= route('categories.update', ['category' => $category->code_category]) ?>" method="POST">
                <div class="form-group">
                    <label for="name">Nom</label>
                    <input type="text" name="name" class="form-control" id="name" value="<?= $category->name ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea rows="3" class="form-control" name="description" id="description"><?= $category->description ?></textarea>
                </div>
                <button class="btn btn-block btn-success" type="submit">Modifier</button>
            </form>
        </div>
    </div>
</div>