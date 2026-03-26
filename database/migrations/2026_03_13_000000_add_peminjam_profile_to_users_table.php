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
        Schema::table('users', function (Blueprint $table) {
            $table->string('kelas')->nullable()->after('role');
            $table->string('bidang_ajar')->nullable()->after('kelas');
            $table->string('nomor_telepon')->nullable()->after('bidang_ajar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['kelas', 'bidang_ajar', 'nomor_telepon']);
        });
    }
};
