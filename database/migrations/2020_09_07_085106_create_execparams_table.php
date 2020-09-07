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
            $table->unsignedBigInteger('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('ass');
            $table->boolean('cnv');
            $table->boolean('snv');
            $table->boolean('genus');
            $table->string('genus_name', 200);
            $table->boolean('resfinder_db');
            $table->string('resfinder_db_path', 200);
            $table->boolean('nt_db');
            $table->string('nt_db_path', 200);
            $table->boolean('kraken_db');
            $table->string('kraken_db_path', 200);
            $table->boolean('eggnog');
            $table->string('eggnog_path', 200);
            $table->boolean('kofam_profile');
            $table->string('kofam_profile_path', 200);
            $table->boolean('kofam_kolist');
            $table->string('kofam_kolist_path', 200);
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
