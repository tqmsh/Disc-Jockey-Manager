<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('announcments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender');
            $table->longText('schools')->nullable();
            $table->longText('users')->nullable();
            $table->tinyInteger('globals')->nullable();
            $table->string('type')->nullable();
            $table->string('subject')->nullable();
            $table->string('message')->nullable();
            $table->dateTime('date');
            $table->tinyInteger('state')->nullable();
            $table->integer('order')->nullable();

            $table->foreign('sender')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
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
        Schema::dropIfExists('announcments');
    }
};
