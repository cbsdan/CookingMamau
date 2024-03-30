<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('mode');
            $table->decimal('amount', 12, 2);
            $table->unsignedBigInteger('id_buyer');
            $table->unsignedBigInteger('id_order');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_buyer')->references('id')->on('buyers')->onDelete('cascade');
            $table->foreign('id_order')->references('id')->on('orders')->onDelete('cascade');

            // Indexes
            $table->index('id_buyer');
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
        Schema::dropIfExists('payments');
    }
}
