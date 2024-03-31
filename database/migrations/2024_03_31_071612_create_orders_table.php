<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_status');
            $table->string('buyer_note')->nullable();
            $table->string('buyer_name');
            $table->string('delivery_address');
            $table->string('email_address');
            $table->decimal('shipping_cost', 12, 2)->default(50);
            $table->string('discount_code')->nullable();
            $table->unsignedBigInteger('id_schedule');
            $table->unsignedBigInteger('id_buyer');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_schedule')->references('id')->on('available_schedules')->onDelete('cascade');
            $table->foreign('id_buyer')->references('id')->on('buyers')->onDelete('cascade');
            $table->foreign('discount_code')->references('discount_code')->on('discounts')->onDelete('set null');

            // Indexes
            $table->index('id_schedule');
            $table->index('id_buyer');
            $table->index('discount_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
