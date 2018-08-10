<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lg_cms_elements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->unsignedInteger('block_type_id');
            $table->unsignedInteger('element_type_id');
            $table->unsignedInteger('sort_nr');
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
        Schema::dropIfExists('lg_cms_elements');
    }
}
