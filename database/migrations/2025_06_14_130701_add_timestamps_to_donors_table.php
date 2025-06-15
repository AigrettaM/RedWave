<?php
// database/migrations/xxxx_add_timestamps_to_donors_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('donors', function (Blueprint $table) {
            // Hanya tambahkan kolom timestamp yang diperlukan
            $table->timestamp('approved_at')->nullable()->after('notes');
            $table->timestamp('completed_at')->nullable()->after('approved_at');
        });
    }

    public function down()
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->dropColumn(['approved_at', 'completed_at']);
        });
    }
};
