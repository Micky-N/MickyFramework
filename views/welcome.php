<table id="table"></table>
<div class="modal fade" id="imageModal" data-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0 m-0">
                <img id="photo" class="img-thumbnail" width="700" height="700" src="">
            </div>
        </div>
    </div>
</div>

<script>
  var $table = $('#table')
  var categories = JSON.parse('<?= json_encode($categories) ?>')

  function categories_Table($el, categories) {
      var cells = Object.keys(categories[0]).filter(c => !['products'].includes(c))
      var i;
      var j;
      var row
      var columns = []
      var data = []

      for (i = 0; i < cells.length; i++) {
        columns.push({
          field: cells[i],
          title: cells[i],
          sortable: true
        })
      }
      columns.push({
        field: 'show',
        title: 'Voir',
      })

      for (i = 0; i < categories.length; i++) {
        row = {}
        for (j = 0; j < cells.length; j++) {
          row[cells[j]] = categories[i][cells[j]]
        }
        row['show'] = `
        <div class="d-flex justify-content-center">
        <a href="categories/${categories[i].code_category}" class="btn btn-outline-success mr-1"><i class="fa fa-eye"></i></a>
        </div>
        `
        data.push(row)
      }
      $el.bootstrapTable({
        columns: columns,
        data: data,
        classes: 'table table-bordered table-sm',
        theadClasses: 'table-primary',
        detailView: cells.length > 1,
        onExpandRow: function(index, row, $detail) {
          var products = categories.find(cat => cat.code_category == row.code_category).products
          products.forEach(p => {
            p.Categorie = row.name
            delete p.code_category
          })
          productsTable($detail.html('<table></table>').find('table'), products)
        }
      })
  }

  function productsTable($el, products) {
    var cells = Object.keys(products[0]).filter(p => !['suppliers'].includes(p))
    var i;
    var j;
    var row
    var columns = []
    var data = []

    for (i = 0; i < cells.length; i++) {
      columns.push({
        field: cells[i],
        title: cells[i],
        sortable: true
      })
    }
    columns.push({
      field: 'show',
      title: 'Voir',
    })

    for (i = 0; i < products.length; i++) {
      row = {}
      for (j = 0; j < cells.length; j++) {
        row[cells[j]] = products[i][cells[j]]
      }
      row['show'] = `
      <div class="d-flex justify-content-center">
      <a href="products/${products[i].code_product}" class="btn btn-outline-success mr-1"><i class="fa fa-eye"></i></a>
      </div>
      `
      data.push(row)
    }
    $el.bootstrapTable({
      columns: columns,
      classes: 'table table-bordered table-sm',
      theadClasses: 'table-warning',
      data: data,
      pagination: true,
      pageList: [5, 10, 20],
      pageSize: 5,
      paginationParts: ['pageList', 'pageSize'],
      detailView: cells.length > 1,
      onExpandRow: function(index, row, $detail) {
        var suppliers = products.find(produit => produit.code_product == row.code_product).suppliers
        suppliersTable($detail.html('<table></table>').find('table'), suppliers)
      }
    })
  }


  function suppliersTable($el, suppliers) {
    var cells = Object.keys(suppliers[0])
    var i;
    var j;
    var row
    var columns = []
    var data = []

    for (i = 0; i < cells.length; i++) {
      columns.push({
        field: cells[i],
        title: cells[i],
        sortable: true
      })
    }
    columns.push({
      field: 'show',
      title: 'Voir',
    })


    for (i = 0; i < suppliers.length; i++) {
      row = {}
      for (j = 0; j < cells.length; j++) {
        row[cells[j]] = suppliers[i][cells[j]]
      }
      row['show'] = `
      <div class="d-flex justify-content-center">
      <a href="suppliers/${suppliers[i].code_supplier}" class="btn btn-outline-success mr-1"><i class="fa fa-eye"></i></a>
      </div>
      `
      data.push(row)
    }
    $el.bootstrapTable({
      columns: columns,
      data: data,
      classes: 'table table-bordered table-sm',
      theadClasses: 'table-success',
    })
  }

  function showModal(e) {
    var photo = e.getAttribute('data-ref')
    $('img#photo').attr('src', photo)
    $('#imageModal').modal('toggle')
  }

  $(function() {
    categories_Table($table, categories)
  })
</script>