<button class="btn btn-success" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i> Ajouter un produit</button>
<table id="table"></table>
<div class="modal fade" id="imageModal" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-0 m-0">
        <img id="photo" class="img-thumbnail" width="700" height="700" src="">
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="createModal" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body py-0">
        <div class="col p-0">
          <div class="modal-header">
            <h4 class="d-flex justify-content-between align-items-center m-0">
              <span class="text-muted">Créer un produit</span>
            </h4>
          </div>
          <form action="<?= route('products/create') ?>" class="px-2 pt-4">
            <div class="row">
              <div class="col-md-6 form-group">
                <label for="name">Nom</label>
                <input type="text" name="name" class="form-control" id="name" required>
              </div>
              <div class="col form-group">
                <label for="selling_price">Prix de vente</label>
                <input type="number" step="0.01" class="form-control" name="selling_price" id="selling_price" required>
              </div>
            </div>
            <div class="mb-3">
              <div class="row">
                <div class="col form-group">
                  <label for="photo">Photo</label>
                  <input type="file" name="photo" id="photo" class="form-control">
                </div>
                <div class="col form-group">
                  <label for="code_category">Catégorie</label>
                  <select class="form-control" name="code_category" id="code_category" required>
                    <option value="" selected>Aucune catégorie selectionner</option>
                    <?php foreach ($categories as $category): ?>
                      <option value="<?= $category->code_category ?> "> <?= $category->name ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-block btn-success" type="submit">Créer</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  var $table = $('#table')
  var products = JSON.parse('<?= json_encode($products); ?>');

  function productsTable($el, products) {
    var productsCells = Object.keys(products[0]).filter(p => !['categorie', 'code_category', 'user_id'].includes(p))
    var i;
    var j;
    var row
    var columns = []
    var data = []

    columns.push({
      field: 'state',
      checkbox: true,
    })
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
      forceHide: true
    })
    for (i = 0; i < products.length; i++) {
      row = {}
      for (j = 0; j < productsCells.length; j++) {
        row[productsCells[j]] = products[i][productsCells[j]]
      }
      row['Actions'] = `
            <div class='d-flex flex-row justify-content-center'>
                <a href="/admin/products/${products[i].code_product}" class='btn btn-outline-success mr-1'><i class='fa fa-eye'></i></a>
                <a href="/admin/products/delete/${products[i].code_product}" class='btn btn-outline-danger'><i class='fa fa-trash'></i></a>
            </div>
            `
      data.push(row)

    }
    $el.bootstrapTable({
      columns: columns,
      data: data,
      classes: 'table table-sm',
      theadClasses: 'table-warning',
      exportDataType: 'selected',
      exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'pdf'],
      exportOptions: {
        fileName: 'Produit_Export',
        displayTableName: true,
        htmlContent: true,
      },
      search: true,
      showExport: true,
      exportHiddenColumns: false,
      maintainMetaData: true,
      clickToSelect: true,
      pagination: true,
      paginationParts: ['pageList', 'pageSize'],
      pageList: ['5', '15', '25'],
      pageSize: '5',
    })
  }

  function showModal(e) {
    var photo = e.getAttribute('data-ref')
    $('img#photo').attr('src', photo)
    $('#imageModal').modal('toggle')
  }

  $(function() {
    productsTable($table, products)
  })
</script>