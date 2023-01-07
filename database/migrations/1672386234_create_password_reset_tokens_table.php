<?php


use MkyCore\Abstracts\Migration;
use MkyCore\Migration\MigrationTable;
use MkyCore\Migration\Schema;

class CreatePasswordResetTokensTable extends Migration
{

    public function up()
    {
        Schema::create('password_reset_tokens', function(MigrationTable $table){
            $table->id()->autoIncrement();
            $table->string('entity', 40)->notNull();
            $table->integer('entity_id')->notNull();
            $table->string('token')->notNull();
            $table->datetime('expires_at')->notNull();
            $table->createdAt();
        });
    }

    public function down()
    {
        Schema::dropTable('password_reset_tokens');
    }

}