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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_creator');

            $table->string('event_name')->nullable();
            $table->dateTime('event_start_time')->nullable();
            $table->dateTime('event_finish_time')->nullable();
            $table->string('school')->index()->nullable();
            $table->tinyText('event_address')->nullable();
            $table->text('event_zip_postal')->nullable();
            $table->text('event_info')->nullable();
            $table->longText('event_rules');
            
            $table->foreign('event_creator')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('school')
                ->references('school_name')
                ->on('schools')
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
        Schema::dropIfExists('events');
    }
};
