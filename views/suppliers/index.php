<button class="btn btn-success" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i> Ajouter un fournisseur</button>
<table id="table"></table>
<div class="modal fade" id="createModal" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body py-0">
        <div class="col p-0">
          <div class="modal-header">
            <h4 class="d-flex justify-content-between align-items-center m-0">
              <span class="text-muted">Créer un fournisseur</span>
            </h4>
          </div>
          <form action="<?= route('suppliers.create') ?>" method="post" class="px-2 pt-4">
            <div class="row">
              <div class="col-md-4">
                <label for="name">Nom</label>
                <input type="text" name="name" class="form-control" id="name" required>
              </div>
              <div class="col-md-2">
                <label for="num_street">Numero voie</label>
                <input type="text" class="form-control" name="num_street" id="num_street">
              </div>
              <div class="col-md-6">
                <label for="name_street">Nom de la voie</label>
                <input type="text" class="form-control" name="name_street" required>
              </div>
            </div>
            <div class="mb-3">
              <div class="row">
                <div class="col">
                  <label for="postcode">Code Postal</label>
                  <input type="text" class="form-control" name="postcode" id="postcode">
                </div>
                <div class="col">
                  <label for="city">Ville</label>
                  <input type="text" class="form-control" name="city" required>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="informations">Informations</label>
              <textarea row="1" class="form-control" name="informations" id="informations"></textarea>
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
  var suppliers = JSON.parse('<?= json_encode($suppliers) ?>')

  function suppliersTable($el, suppliers) {
    var suppliersCells = Object.keys(suppliers[0])
    var i;
    var j;
    var row
    var columns = []
    var data = []

    columns.push({
      field: 'state',
      checkbox: true,
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
      forceHide: true
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
      columns: columns,
      data: data,
      exportDataType: 'selected',
      exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'pdf'],
      exportOptions: {
        fileName: function() {
          return 'Producteur_Export'
        }
      },
      search: true,
      showExport: true,
      exportHiddenColumns: false,
      maintainMetaData: true,
      clickToSelect: true,
      pagination: true,
      paginationParts: ['pageList', 'pageSize'],
      pageList: ['25', '50', '100'],
      pageSize: '25',
    })
  }


  $(function() {
    suppliersTable($table, suppliers)
  })
</script>