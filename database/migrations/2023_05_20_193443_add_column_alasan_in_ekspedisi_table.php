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
        Schema::table('ekspedisi', function (Blueprint $table) {
            $table->string('alasan_jenis_layanan_other')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ekspedisi', function (Blueprint $table) {
            $table->dropColumn('alasan_jenis_layanan_other');
        });
    }
};
