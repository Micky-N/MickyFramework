<div class="col">
    <h2>Créer un supplier</h2>
    <form class="needs-validation" action="<?= route('suppliers.create') ?>" method="POST">
        <div class="row">
            <div class="col-md-4">
                <label for="name">Nom</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="col-md-2">
                <label for="num_street">Numéro de la voie</label>
                <input type="text" class="form-control" name="num_street" required>
            </div>
            <div class="col-md-6">
                <label for="name_street">Nom de la voie</label>
                <input type="text" class="form-control" name="name_street" required>
            </div>
        </div>
        <div class="mb-3">
            <div class="row">
                <div class="col">
                    <label for="postcode">Code postal</label>
                    <input type="text" class="form-control" name="postcode" required>
                </div>
                <div class="col">
                    <label for="city">Ville</label>
                    <input type="text" class="form-control" name="city" required>
                </div>
            </div>
        </div>
        <button class="btn btn-block btn-success" type="submit">Créer</button>
    </form>
</div>

<table id="table"></table>

<script>
    var $table = $('#table')
    var suppliers = JSON.parse('<?= json_encode($suppliers); ?>')

    function suppliersTable($el, suppliers) {
        var suppliersCells = Object.keys(suppliers[0]).filter(c => !['suppliers'].includes(c))
        var i;
        var j;
        var row
        var columns = []
        var data = []

        for (i = 0; i < suppliersCells.length; i++) {
            columns.push({
                field: suppliersCells[i],
                title: suppliersCells[i],
                sortable: true
            })
        }

        columns.push({
            field: 'Actions',
            title: 'Actions',
        })
        for (i = 0; i < suppliers.length; i++) {
            row = {}
            for (j = 0; j < suppliersCells.length; j++) {
                row[suppliersCells[j]] = suppliers[i][suppliersCells[j]]
            }
            row['Actions'] = `
            <div class='d-flex flex-row justify-content-center'>
                <a href="suppliers/${suppliers[i].code_supplier}" class='btn btn-outline-success mr-1'><i class='fa fa-eye'></i></a>
                <a href="suppliers/delete/${suppliers[i].code_supplier}" class='btn btn-outline-danger'><i class='fa fa-trash'></i></a>
            </div>
            `
            data.push(row)
        }
        $el.bootstrapTable({
            classes: 'table table-bordered text-center table-sm',
            theadClasses: 'table-success',
            columns,
            search: true,
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
        suppliersTable($table, suppliers)
    })
</script>