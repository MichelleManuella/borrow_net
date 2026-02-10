<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   // database/migrations/xxxx_create_peminjaman_table.php
public function up()
{
    Schema::create('peminjaman', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('alat_id')->constrained()->onDelete('cascade');
        $table->date('tanggal_pinjam');
        $table->date('tanggal_kembali')->nullable();
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->timestamps();
    });
}
};
