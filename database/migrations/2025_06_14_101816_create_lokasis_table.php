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
        Schema::create('lokasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('alamat');
            $table->string('kota', 100);
            $table->string('provinsi', 100)->default('DKI Jakarta');
            $table->date('tanggal_operasional');
            $table->time('jam_buka')->nullable();
            $table->time('jam_tutup')->nullable();
            $table->string('kontak', 20)->nullable();
            $table->integer('kapasitas')->nullable();
            $table->string('gambar')->nullable(); // untuk menyimpan path gambar
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');
            $table->enum('jenis', ['provinsi', 'kota', 'cabang'])->default('kota');
            $table->decimal('latitude', 10, 8)->nullable(); // untuk koordinat GPS
            $table->decimal('longitude', 11, 8)->nullable(); // untuk koordinat GPS
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasis');
    }
};
