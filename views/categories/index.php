<button class="btn btn-success" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i> Ajouter une catégorie</button>
<table id="table"></table>
<div class="modal fade" id="createModal" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body py-0">
        <div class="col p-0">
          <div class="modal-header">
            <h4 class="d-flex justify-content-between align-items-center m-0">
              <span class="text-muted">Créer une catégorie</span>
            </h4>
          </div>
          <form action="categories/create" method="post" class="px-2 pt-4">
            <div class="form-group">
              <label for="name">Nom</label>
              <input type="text" class="form-control" name="name" id="name" required>
            </div>
            <div class="form-group">
              <label for="description">Description</label>
              <textarea rows="3" class="form-control" name="description" id="description"></textarea>
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
  var categories = JSON.parse('<?= json_encode($categories) ?>')

  function categoriesTable($el, categories) {
    var categoriesCells = Object.keys(categories[0])
    var i;
    var j;
    var row
    var columns = []
    var data = []

    columns.push({
      field: 'state',
      checkbox: true,
    })
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
      forceHide: true
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
      columns: columns,
      data: data,
      exportDataType: 'selected',
      exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'pdf'],
      exportOptions: {
        fileName: function() {
          return 'Categorie_Export'
        }
      },
      search: true,
      showExport: true,
      exportHiddenColumns: false,
      maintainMetaData: true,
      clickToSelect: true,
      pagination: true,
      paginationParts: ['pageList', 'pageSize'],
      pageList: ['5', '10', '15'],
      pageSize: '10',
    })
  }


  $(function() {
    categoriesTable($table, categories)
  })
</script>