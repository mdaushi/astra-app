<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kegiatan_perjalanan_dinas', function (Blueprint $table) {
            $table->id();

            $table->integer('pengajuan_perjalanan_dinas_id');
            $table->date('tanggal');

            $table->string('dari_kota');
            $table->string('ke_kota');

            $table->text('kegiatan_pokok');

            $table->time('berangkat_jam');
            $table->time('tiba_jam');

            $table->string('maskapai');

            $table->string('payment_via'); //beli sendiri, via GA

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_perjalanan_dinas');
    }
};
