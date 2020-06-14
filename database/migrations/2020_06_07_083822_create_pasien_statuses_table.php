<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasienStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pasien_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pasien_id');
            $table->text('keterangan_status');
            $table->date('tanggal_status');
            $table->string('status',50);
            $table->index('pasien_id');
            $table->foreign('pasien_id')->references('id')->on('pasiens')->onDelete('RESTRICT')->onUpdate('RESTRICT');
            $table->timestamps();
            $table->softDeletes();
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
