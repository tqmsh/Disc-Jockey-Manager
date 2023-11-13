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
        Schema::create('student_specs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('gender');
            $table->string('height_feet')->nullable();
            $table->string('height_inches')->nullable();
            $table->integer('weight_pounds')->nullable();
            $table->string('hair_color')->nullable();
            $table->string('hair_style')->nullable();
            $table->string('hair_length')->nullable();
            $table->string('skin_complexion')->nullable();
            $table->string('eye_color')->nullable();
            $table->string('lip_style')->nullable();
            $table->string('bust')->nullable();
            $table->string('waist')->nullable();
            $table->string('hips')->nullable();
            $table->text('notes')->nullable();
            $table->string('body_type')->nullable();
            $table->timestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_specs');
    }
};
