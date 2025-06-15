<?php
// database/migrations/2025_06_15_create_blood_stocks_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blood_stocks', function (Blueprint $table) {
            $table->id();
            $table->enum('blood_type', ['A', 'B', 'AB', 'O']);
            $table->enum('rhesus', ['POSITIF', 'NEGATIF'])->default('POSITIF');
            $table->integer('stock_quantity')->default(0);
            $table->date('last_updated_date')->default(now());
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['blood_type', 'rhesus']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('blood_stocks');
    }
};
