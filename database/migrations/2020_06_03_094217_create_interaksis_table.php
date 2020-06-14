<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInteraksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interaksis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pasien_id');
            $table->text('keterangan');
            $table->date('tanggal_interaksi');
            $table->text('lokasi')->nullable();
            $table->string('koordinat_lokasi',100)->nullable();
            $table->unsignedBigInteger('kelurahan_id')->nullable();
            $table->unsignedBigInteger('kecamatan_id')->nullable();
            $table->unsignedBigInteger('kota_id')->nullable();
            $table->unsignedBigInteger('provinsi_id');
            $table->timestamps();
            $table->softDeletes();

            $table->index('pasien_id');
            $table->index('provinsi_id');
            $table->index('kota_id', 'kota_id');
            $table->foreign('pasien_id')->references('id')->on('pasiens')->onDelete('RESTRICT')->onUpdate('RESTRICT');
            $table->foreign('provinsi_id')->references('id')->on('provinsis')->onDelete('RESTRICT')->onUpdate('RESTRICT');
            $table->foreign('kota_id')->references('id')->on('kotas')->onDelete('RESTRICT')->onUpdate('RESTRICT');
         

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interaksis');
    }
}
