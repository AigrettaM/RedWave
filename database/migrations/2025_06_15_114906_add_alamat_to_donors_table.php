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
            $table->string('alamat')->nullable(); // Hapus 'after' karena 'lokasi_id' tidak ada
        });
    }

    public function down()
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->dropColumn('alamat');
        });
    }
};
