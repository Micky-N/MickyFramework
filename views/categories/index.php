<div class="col">
    <h2>Créer une catégories</h2>
    <form class="needs-validation row" action="<?= route('categories.create') ?>" method="POST">
        <div class="col-md-8 row">
            <div class="col-md-4">
                <label for="name">Nom</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="col-md-6">
                <label for="description">Description</label>
                <input type="text" class="form-control" name="description" required>
            </div>
            <button class="btn btn-warning" type="submit">Créer</button>
        </div>
    </form>
</div>

<table id="table"></table>

<script>
    var $table = $('#table')
    var categories = JSON.parse('<?= json_encode($categories); ?>')

    function categoriesTable($el, categories) {
        var categoriesCells = Object.keys(categories[0]).filter(c => !['products'].includes(c))
        var i;
        var j;
        var row
        var columns = []
        var data = []

        for (i = 0; i < categoriesCells.length; i++) {
            columns.push({
                field: categoriesCells[i],
                title: categoriesCells[i],
                sortable: true
            })
        }

        columns.push({
            field: 'Actions',
            title: 'Actions',
        })
        for (i = 0; i < categories.length; i++) {
            row = {}
            for (j = 0; j < categoriesCells.length; j++) {
                row[categoriesCells[j]] = categories[i][categoriesCells[j]]
            }
            row['Actions'] = `
            <div class='d-flex flex-row justify-content-center'>
                <a href="categories/${categories[i].code_category}" class='btn btn-outline-success mr-1'><i class='fa fa-eye'></i></a>
                <a href="categories/delete/${categories[i].code_category}" class='btn btn-outline-danger'><i class='fa fa-trash'></i></a>
            </div>
            `
            data.push(row)
        }
        $el.bootstrapTable({
            classes: 'table table-bordered text-center table-sm',
            theadClasses: 'table-warning',
            columns,
            data,
            pagination: true,
            paginationParts: ['pageList', 'pageSize'],
            pageList: ['5', '10', '15'],
            pageSize: "5",
            showPrint: true,
            showFullscreen: true,
        })
    }

    $(function() {
        categoriesTable($table, categories)
    })
</script>