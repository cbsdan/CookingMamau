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
            $table->integer('rating');
            $table->string('comment')->nullable();
            $table->unsignedBigInteger('id_order');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_order')->references('id')->on('orders')->onDelete('cascade');

            // Index
            $table->index('id_order');
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
