<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kotas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kota', 100);
            $table->unsignedBigInteger('provinsi_id');
            $table->timestamps();
            $table->softDeletes();
            $table->index('provinsi_id');
            $table->foreign('provinsi_id')->references('id')->on('provinsis')->onDelete('RESTRICT
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
        Schema::dropIfExists('kotas');
    }
}
