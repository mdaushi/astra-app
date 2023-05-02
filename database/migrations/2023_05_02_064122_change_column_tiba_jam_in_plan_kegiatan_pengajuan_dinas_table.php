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
        Schema::table('plan_kegiatan_pengajuan_dinas', function (Blueprint $table) {
            $table->time('tiba_jam')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plan_kegiatan_pengajuan_dinas', function (Blueprint $table) {
            //
        });
    }
};
