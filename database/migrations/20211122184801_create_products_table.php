<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateProductsTable extends AbstractMigration
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
        $table = $this->table('products', ['id' => 'code_product']);
        $table->addColumn('code_category', MysqlAdapter::PHINX_TYPE_INTEGER)
              ->addColumn('name', 'string', ['limit' => 100])
              ->addColumn('photo', 'text', ['limit' => 100])
              ->addForeignKey('code_category', 'categories', 'code_category', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
              ->create();
    }
}
