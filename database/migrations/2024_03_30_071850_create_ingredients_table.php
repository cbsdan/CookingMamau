<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('qty');
            $table->string('unit')->nullable();
            $table->string('image_path')->nullable();
            $table->unsignedBigInteger('id_baked_goods');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_baked_goods')->references('id')->on('baked_goods')->onDelete('cascade');

            // Index
            $table->index('id_baked_goods');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingredients');
    }
}
