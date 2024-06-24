<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('rating')->unsigned();
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('id_order');
            $table->unsignedBigInteger('id_baked_goods');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign(['id_order', 'id_baked_goods'])
                  ->references(['id_order', 'id_baked_goods'])
                  ->on('ordered_goods')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_reviews');
    }
}
