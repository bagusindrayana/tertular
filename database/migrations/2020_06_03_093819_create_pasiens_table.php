<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasiensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pasiens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('klaster_id');
            $table->string('no',10);
            $table->string('nama_lengkap',191);
            $table->date('tanggal_lahir');
            $table->text('alamat')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('jenis_kelamin',20);
            $table->unsignedBigInteger('kelurahan_id')->nullable();
            $table->unsignedBigInteger('kecamatan_id')->nullable();
            $table->unsignedBigInteger('kota_id');
            $table->unsignedBigInteger('provinsi_id');
            $table->text('lokasi')->nullable();
            $table->string('koordinat_lokasi',100)->nullable();
            $table->unsignedBigInteger('lokasi_kelurahan_id')->nullable();
            $table->unsignedBigInteger('lokasi_kecamatan_id')->nullable();
            $table->unsignedBigInteger('lokasi_kota_id');
            $table->unsignedBigInteger('lokasi_provinsi_id');
            $table->date('lokasi_tanggal')->nullable();
            // $table->string('status',50);
            $table->timestamps();
            $table->softDeletes();
            $table->index('provinsi_id');
            $table->index('klaster_id');
            $table->foreign('provinsi_id')->references('id')->on('provinsis')->onDelete('RESTRICT')->onUpdate('RESTRICT');
            $table->foreign('kota_id')->references('id')->on('kotas')->onDelete('RESTRICT')->onUpdate('RESTRICT');
            $table->foreign('lokasi_provinsi_id')->references('id')->on('provinsis')->onDelete('RESTRICT')->onUpdate('RESTRICT');
            $table->foreign('lokasi_kota_id')->references('id')->on('kotas')->onDelete('RESTRICT')->onUpdate('RESTRICT');
            $table->foreign('klaster_id')->references('id')->on('klasters')->onDelete('RESTRICT')->onUpdate('RESTRICT');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pasiens');
    }
}
