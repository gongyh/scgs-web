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
            $table->string('sampleLabel', 50);
            $table->string('library_id', 150);
            $table->string('library_strategy', 200);
            $table->string('library_source', 100);
            $table->string('library_selection', 100);
            $table->integer('pairends');
            $table->string('platform', 100);
            $table->string('instrument_model', 200);
            $table->string('design_description', 500);
            $table->string('filetype', 50);
            $table->string('filename1', 400);
            $table->string('filename2', 400)->nullable();
            $table->unsignedBigInteger('species_id')->nullable();
            $table->foreign('species_id')->references('id')->on('species')->onDelete('cascade');
            $table->unsignedBigInteger('applications_id');
            $table->foreign('applications_id')->references('id')->on('applications')->onDelete('cascade');
            $table->unsignedBigInteger('projects_id');
            $table->foreign('projects_id')->references('id')->on('projects')->onDelete('cascade');
            $table->tinyInteger('isPrepared')->default(0);
            $table->tinyInteger('status')->default(0);
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
