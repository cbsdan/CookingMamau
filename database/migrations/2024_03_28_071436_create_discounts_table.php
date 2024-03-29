<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->string('discount_code')->primary();
            $table->integer('percent');
            $table->integer('max_number_buyer')->nullable();
            $table->decimal('min_order_price', 12, 2)->nullable();
            $table->tinyInteger('is_one_time_use');
            $table->date('discount_start');
            $table->date('discount_end');
            $table->string('image_path')->nullable();
            $table->decimal('max_discount_amount', 12, 2)->nullable();
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
        Schema::dropIfExists('discounts');
    }
}
