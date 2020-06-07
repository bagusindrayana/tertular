<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasiesnSatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pasiesn_satuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pasien_id');
            $table->text('keterangan');
            $table->date('tanggal_status');
            $table->string('status',50);
            $table->index('pasien_id', 'pasien_id');
            $table->foreign('pasien_id', 'pasien_id')->references('id')->on('pasiens')->onDelete('RESTRICT
')->onUpdate('RESTRICT');
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
        Schema::dropIfExists('pasiesn_satuses');
    }
}
