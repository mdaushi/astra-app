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
        Schema::create('ekspedisi', function (Blueprint $table) {
            $table->id();
            $table->string('kategori'); //faktur dan nonfaktur
            $table->string('ekspedisi');
            $table->date('tanggal');
            $table->string('tempat_tujuan');
            $table->string('alamat_tujuan');
            $table->string('nama_penerima');
            $table->string('kontak')->nullable(); //kontak yang bisa dihubungi
            $table->string('nama_pengirim');
            $table->string('jenis_paket');
            $table->string('jenis_layanan'); //dropdown yes/ods/reguler
            $table->string('no_resi')->nullable();
            // $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ekspedisi');
    }
};
