<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('samples', function (Blueprint $table) {
            $table->id();
            $table->string('filename1', 400);
            $table->string('filename2', 400);
            $table->integer('pairends');
            $table->integer('species_id');
            $table->string('sampleLabel', 50);
            $table->foreign('species_id')->references('id')->on('species')->onDelete('cascade');
            $table->integer('applications_id');
            $table->foreign('applications_id')->references('id')->on('applications')->onDelete('cascade');
            $table->integer('projects_id');
            $table->foreign('projects_id')->references('id')->on('projects')->onDelete('cascade');
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
        Schema::dropIfExists('samples');
    }
}
