<h1>Page d'Accueil !</h1>
<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eaque optio deserunt ipsum aut nemo! Obcaecati, voluptates accusantium maiores deserunt, facere qui tenetur sed perspiciatis, doloribus fugit molestias nihil placeat itaque.</p>

<!-- <?php dump($categories) ?> -->
<table id="table" data-show-header="false"></table>

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
            columns,
            data,
            showPrint: true,
            showFullscreen: true,
            detailView: categoriesCells.length > 1,
            onExpandRow: function(index, row, $detail) {
                let products = categories.find(cat => cat.code_category == row.code_category).products
                productsTable($detail.html('<table></table>').find('table'), products)
            }
        })
    }

    function productsTable($el, products) {
        products.forEach(p => {
            delete Object.assign(p, {
                ['quantity']: p['stock_quantity']
            })['stock_quantity'];
        })
        var productsCells = Object.keys(products[0]).filter(p => !['suppliers', 'code_category'].includes(p))
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
            classes: 'table table-bordered table-sm',
            theadClasses: 'table-primary',
            pagination: true,
            height: 400,
            paginationParts: ['pageList', 'pageSize'],
            pageList: ['5', '10', '15'],
            pageSize: "5",
            columns: columns,
            data: data,
            detailView: productsCells.length > 1,
            onExpandRow: function(index, row, $detail) {
                let suppliers = products.find(pro => pro.code_product == row.code_product).suppliers
                suppliersTable($detail.html('<table></table>').find('table'), suppliers)
            }
        })
    }

    function suppliersTable($el, suppliers) {
        var suppliersCells = Object.keys(suppliers[0]).filter(s => !['id', 'code_product'].includes(s))
        var i;
        var j;
        var row
        var columns = []
        var data = []
        suppliers.forEach(s => {
            s.price = new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR'
            }).format(s.price)
        })

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
            classes: 'table table-bordered table-sm mt-2',
            theadClasses: 'table-success',
            columns: columns,
            data: data
        })
    }

    $(function() {
        categoriesTable($table, categories)
        $table.find('tr').addClass('table-warning')
    })
</script>