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
        Schema::create('pengajuan_perjalanan_dinas', function (Blueprint $table) {
            $table->id();

            // format penomoran surat
            $table->string('no_surat')->nullable();
            $table->string('nama')->nullable();
            $table->string('golongan')->nullable();
            $table->string('penginapan')->nullable(); //hotel, rumah sendiri, other

            $table->string('payment')->nullable(); //tunai, transfer
            $table->string('nama_bank')->nullable();
            $table->string('no_rekening')->nullable();

            $table->integer('pegawai_id');

            // sign
            $table->timestamp('sign_user_at')->nullable();
            $table->timestamp('sign_chief_at')->nullable();
            $table->string('nama_chief_signed')->nullable();

            $table->timestamp('sign_hrd_at')->nullable();
            $table->string('nama_hrd_signed')->nullable();

            $table->timestamp('sign_ga_at')->nullable();
            $table->string('nama_ga_signed')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_perjalanan_dinas');
    }
};
