<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyers', function (Blueprint $table) {
            $table->id();
            $table->string('fname');
            $table->string('lname');
            $table->string('contact');
            $table->string('address')->nullable();
            $table->string('barangay')->nullable();
            $table->string('city')->nullable();
            $table->string('landmark')->nullable();
            $table->unsignedBigInteger('id_user');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index('id_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buyers');
    }
}
