<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateSuppliersTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $exists = $this->hasTable('suppliers');
        if (!$exists) {
            $table = $this->table('suppliers', ['id' => 'code_supplier']);
            $table->addColumn('name', 'string', ['limit' => 25])
                ->addColumn('informations', 'string', ['limit' => 100])
                ->addColumn('num_street', 'string', ['limit' => 10])
                ->addColumn('name_street', 'string', ['limit' => 50])
                ->addColumn('postcode', 'string', ['limit' => 6])
                ->addColumn('city', 'string', ['limit' => 50])
                ->addIndex('name', ['unique' => true])
                ->create();
        }
    }
}
