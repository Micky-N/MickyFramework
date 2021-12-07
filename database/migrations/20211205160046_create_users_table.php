<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateUsersTable extends AbstractMigration
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
        $exists = $this->hasTable('users');
        if (!$exists) {
            $users = $this->table('users');
            $users->addColumn('username', 'string', ['limit' => 20])
                ->addColumn('password', 'string', ['limit' => 40])
                ->addColumn('email', 'string', ['limit' => 100])
                ->addColumn('first_name', 'string', ['limit' => 30])
                ->addColumn('last_name', 'string', ['limit' => 30])
                ->addColumn('role_id', MysqlAdapter::PHINX_TYPE_INTEGER, ['null' => true])
                ->addColumn('created', 'datetime')
                ->addIndex(['username', 'email'], ['unique' => true])
                ->addForeignKey('role_id', 'roles', 'id', ['delete' => 'SET_NULL', 'update' => 'CASCADE'])
                ->create();
        }
    }
}
