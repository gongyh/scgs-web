<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExecparamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('execparams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('samples_id')->nullable();
            $table->foreign('samples_id')->references('id')->on('samples')->onDelete('cascade');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->boolean('ass');
            $table->boolean('cnv');
            $table->boolean('snv');
            $table->boolean('bulk');
            $table->boolean('saturation');
            $table->boolean('saveTrimmed');
            $table->boolean('euk');
            $table->boolean('fungus');
            $table->boolean('acquired');
            $table->boolean('resume');
            $table->boolean('saveAlignedIntermediates');
            $table->boolean('genus');
            $table->string('genus_name', 200)->nullable();
            $table->string('reference_genome', 200)->nullable();
            $table->boolean('augustus_species');
            $table->string('augustus_species_name', 200)->nullable();
            $table->boolean('resfinder_db');
            $table->boolean('nt_db');
            $table->boolean('kraken_db');
            $table->boolean('eggnog');
            $table->boolean('kofam_profile');
            $table->boolean('kofam_kolist');
            $table->boolean('eukcc_db');
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
        Schema::dropIfExists('execparams');
    }
}
