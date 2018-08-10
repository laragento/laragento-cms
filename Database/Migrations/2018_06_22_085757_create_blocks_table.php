<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lg_cms_blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('classes')->nullable();
            $table->integer('sortnr')->default(0);
            $table->unsignedInteger('page_id');
            $table->unsignedInteger('type_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lg_cms_blocks');
    }
}
