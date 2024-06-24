<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('baked_good_images', function (Blueprint $table) {
            $table->id();
            $table->string('image_path');
            $table->boolean('is_thumbnail')->default(false);
            $table->unsignedBigInteger('id_baked_goods');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_baked_goods')->references('id')->on('baked_goods')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('baked_good_images');
    }
};
