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
            $table->unsignedBigInteger('samples_id');
            $table->foreign('samples_id')->references('id')->on('samples')->onDelete('cascade');
            $table->boolean('ass');
            $table->boolean('cnv');
            $table->boolean('snv');
            $table->boolean('bulk');
            $table->boolean('saturation');
            $table->boolean('saveTrimmed');
            $table->boolean('acquired');
            $table->boolean('resume');
            $table->boolean('saveAlignedIntermediates');
            $table->boolean('genus');
            $table->string('genus_name', 200)->nullable();
            $table->boolean('resfinder_db');
            $table->boolean('nt_db');
            $table->boolean('kraken_db');
            $table->boolean('eggnog');
            $table->boolean('kofam_profile');
            $table->boolean('kofam_kolist');
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
