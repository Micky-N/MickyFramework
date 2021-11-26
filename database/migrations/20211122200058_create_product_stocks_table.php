<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateProductStocksTable extends AbstractMigration
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
        $table = $this->table('product_stocks');
        $table->addColumn('code_product', MysqlAdapter::PHINX_TYPE_INTEGER)
              ->addColumn('code_stock', MysqlAdapter::PHINX_TYPE_INTEGER)
              ->addColumn('quantity', MysqlAdapter::PHINX_TYPE_INTEGER)
              ->addForeignKey('code_product', 'products', 'code_product', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
              ->addForeignKey('code_stock', 'stocks', 'code_stock', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
              ->create();
    }
}
