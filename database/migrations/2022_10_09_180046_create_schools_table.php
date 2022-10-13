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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('school_name')->index();
            $table->string('country');
            $table->string('state_province');
            $table->string('school_board');
            $table->string('address');
            $table->string('zip_postal');
            $table->string('phone_number');
            $table->string('fax')->nullable();
            $table->string('teacher_name');
            $table->string('teacher_email');
            $table->string('teacher_cell');
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
        Schema::dropIfExists('schools');
    }
};
