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

