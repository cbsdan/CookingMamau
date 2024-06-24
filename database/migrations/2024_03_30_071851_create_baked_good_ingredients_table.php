<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBakedGoodIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baked_good_ingredients', function (Blueprint $table) {
            $table->unsignedBigInteger('id_baked_goods');
            $table->unsignedBigInteger('id_ingredients');
            $table->integer('qty')->default(1);
            $table->timestamps();

            // Composite primary key
            $table->primary(['id_baked_goods', 'id_ingredients']);

            // Foreign key constraints
            $table->foreign('id_baked_goods')->references('id')->on('baked_goods')->onDelete('cascade');
            $table->foreign('id_ingredients')->references('id')->on('ingredients')->onDelete('cascade');
        });

        // Indexes
        Schema::table('baked_good_ingredients', function (Blueprint $table) {
            $table->index('id_baked_goods');
            $table->index('id_ingredients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('baked_good_ingredients');
    }
}
