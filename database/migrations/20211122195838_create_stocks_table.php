<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateStocksTable extends AbstractMigration
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
        $exists = $this->hasTable('stocks');
        if (!$exists) {
            $table = $this->table('stocks', ['id' => 'code_stock']);
            $table->addColumn('code_product', MysqlAdapter::PHINX_TYPE_INTEGER)
                ->addColumn('quantity', MysqlAdapter::PHINX_TYPE_INTEGER)
                ->addForeignKey('code_product', 'products', 'code_product', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
                ->addIndex('code_product', ['unique' => true])
                ->create();
        }
    }
}
