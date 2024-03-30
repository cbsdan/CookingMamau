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
        Schema::table('baked_goods', function (Blueprint $table) {
            $table->foreign('thumbnail_image_id')->references('id')->on('baked_good_images')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('baked_goods', function (Blueprint $table) {
            $table->dropForeign(['thumbnail_image_id']);
        });
    }
};
