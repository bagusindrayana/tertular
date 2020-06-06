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
            $table->text('alamat');
            $table->date('tanggal_lahir');
            $table->unsignedBigInteger('kelurahan_id')->nullable();
            $table->unsignedBigInteger('kecamatan_id')->nullable();
            $table->unsignedBigInteger('kota_id')->nullable();
            $table->unsignedBigInteger('provinsi_id');
            $table->text('lokasi');
            $table->string('kordinat_lokasi',100);
            $table->string('status',50);
            $table->date('tanggal_positif');
            $table->timestamps();
            $table->softDeletes();
            $table->index('provinsi_id');
            $table->index('klaster_id');
            $table->foreign('provinsi_id')->references('id')->on('provinsis')->onDelete('RESTRICT
')->onUpdate('RESTRICT');
            $table->foreign('klaster_id')->references('id')->on('klasters')->onDelete('RESTRICT
')->onUpdate('RESTRICT');

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
