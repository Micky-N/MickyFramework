<?php


use MkyCore\Abstracts\Migration;
use MkyCore\Migration\MigrationTable;
use MkyCore\Migration\Schema;

class CreateUsersTable extends Migration
{

    public function up()
    {
        Schema::create('users', function(MigrationTable $table){
            $table->id()->autoIncrement();
            $table->string('name')->notNull();
            $table->string('email')->unique()->notNull();
            $table->string('password')->notNull();
            $table->dates();
        });
    }

    public function down()
    {
        Schema::dropTable('users');
    }

}