<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePipelineParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pipeline_params', function (Blueprint $table) {
            $table->id();
            $table->string('resfinder_db_path', 200);
            $table->string('nt_db_path', 200);
            $table->string('eggnog_db_path', 200);
            $table->string('kraken_db_path', 200);
            $table->string('kofam_profile_path', 200);
            $table->string('kofam_kolist_path', 200);
            $table->string('eukuu_db_path', 200);
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
        Schema::dropIfExists('pipeline_params');
    }
}
