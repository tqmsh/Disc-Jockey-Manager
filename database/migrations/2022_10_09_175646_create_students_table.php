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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->tinyInteger('grade')->nullable();
            $table->bigInteger('phonenumber')->nullable();
            $table->string('email')->nullable();
            $table->string('ticketstatus')->nullable();
            $table->unsignedBigInteger('table_id');
            $table->string('school')->nullable()->index();
            $table->unsignedBigInteger('event_id')->nullable()->index();
            $table->string('allergies')->nullable();

            $table->foreign('table_id')
            ->references('id')
            ->on('seating')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            
            $table->foreign('user_id')
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
        Schema::dropIfExists('students');
    }
};
