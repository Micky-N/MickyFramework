<h1>Producteur <?= $supplier->name ?></h1>
<p>Informations :  <?= $supplier->informations ?>  </p>
<p>Adresse :  <?= $supplier->num_street ?> <?= $supplier->name_street ?> <?= $supplier->city ?> (<?= $supplier->postcode ?>)</p>

<a class="" href="<?= route('suppliers.index') ?>"> <i class="fas fa-arrow-left"></i> Retour aux CRUD Producteurs</a>