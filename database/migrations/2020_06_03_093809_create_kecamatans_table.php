<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKecamatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kecamatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kecamatan', 100);
            $table->unsignedBigInteger('kota_id');
            $table->timestamps();
            $table->softDeletes();
            $table->index('kota_id');
            $table->foreign('kota_id')->references('id')->on('kotas')->onDelete('RESTRICT
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
        Schema::dropIfExists('kecamatans');
    }
}
