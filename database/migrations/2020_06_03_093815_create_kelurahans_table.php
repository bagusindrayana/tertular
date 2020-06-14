<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKelurahansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kelurahans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kecamatan_id');
            $table->string('nama_kelurahan', 100);
            $table->timestamps();
            $table->softDeletes();
            $table->index('kecamatan_id');
            $table->foreign('kecamatan_id')->references('id')->on('kecamatans')->onDelete('RESTRICT
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
        Schema::dropIfExists('kelurahans');
    }
}
