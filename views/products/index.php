<div class="col">
    <h2>Créer un produit</h2>
    <form class="needs-validation row" action="<?= route('products.create') ?>" method="POST">
        <div class="col-md-8 row">
            <div class="col-md-4">
                <label for="name">Nom</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="col-md-6">
                <label for="num_street">Lien photo</label>
                <input type="text" class="form-control" name="num_street" required>
            </div>
            <button class="btn btn-primary" type="submit">Créer</button>
        </div>
    </form>
</div>

<table id="table"></table>

<script>
    var $table = $('#table')
    var products = JSON.parse('<?= json_encode($products); ?>')

    function productsTable($el, products) {
        products.forEach(p => {
            delete Object.assign(p, {
                ['quantity']: p['stock_quantity']
            })['stock_quantity'];
        })
        var productsCells = Object.keys(products[0]).filter(c => !['products'].includes(c))
        var i;
        var j;
        var row
        var columns = []
        var data = []

        for (i = 0; i < productsCells.length; i++) {
            columns.push({
                field: productsCells[i],
                title: productsCells[i],
                sortable: true
            })
        }

        columns.push({
            field: 'Actions',
            title: 'Actions',
        })
        for (i = 0; i < products.length; i++) {
            row = {}
            for (j = 0; j < productsCells.length; j++) {
                row[productsCells[j]] = products[i][productsCells[j]]
            }
            row['Actions'] = `
            <div class='d-flex flex-row justify-content-center'>
                <a href="products/${products[i].code_product}" class='btn btn-outline-success mr-1'><i class='fa fa-eye'></i></a>
                <a href="products/delete/${products[i].code_product}" class='btn btn-outline-danger'><i class='fa fa-trash'></i></a>
            </div>
            `
            data.push(row)
        }
        $el.bootstrapTable({
            classes: 'table table-bordered text-center table-sm',
            theadClasses: 'table-primary',
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
        productsTable($table, products)
    })
</script>