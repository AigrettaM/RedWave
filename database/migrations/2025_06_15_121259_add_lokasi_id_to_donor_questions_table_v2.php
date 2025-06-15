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
        Schema::table('donors', function (Blueprint $table) {
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasis')->onDelete('set null')->after('is_active');
            // Kolom lokasi_id ditambahkan, dengan relasi ke tabel 'lokasis'
        });
    }

    public function down()
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->dropForeign(['lokasi_id']); // Hapus foreign key
            $table->dropColumn('lokasi_id'); // Hapus kolom lokasi_id
        });
    }
};
