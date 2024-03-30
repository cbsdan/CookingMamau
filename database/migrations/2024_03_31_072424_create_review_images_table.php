<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_images', function (Blueprint $table) {
            $table->id();
            $table->string('image_path');
            $table->unsignedBigInteger('id_review');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_review')->references('id')->on('order_reviews')->onDelete('cascade');

            // Index
            $table->index('id_review');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('review_images');
    }
}
