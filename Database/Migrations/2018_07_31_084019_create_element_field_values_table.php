<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElementFieldValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lg_cms_element_field_values', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('block_id');
            $table->unsignedInteger('element_id');
            $table->unsignedInteger('element_field_id');
            // ToDo abstract du different values vor varchar number float string
            $table->text('value')->nullable();
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
        Schema::dropIfExists('lg_cms_element_field_values');
    }
}
