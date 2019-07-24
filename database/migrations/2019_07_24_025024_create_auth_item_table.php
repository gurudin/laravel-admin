<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_item', function (Blueprint $table) {
            $table->string('name', 64);
            $table->string('method', 20);
            $table->tinyInteger('type');
            $table->text('description')->nullable();
            $table->primary(['name', 'method']);
            $table->index('type', 'inx_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auth_item');
    }
}
