<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('donor_code')->nullable();
            $table->string('donor_id')->nullable();
            $table->string('ktp_number')->nullable();
            $table->string('name')->nullable();
            $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable();
            $table->enum('blood_type', ['A', 'B', 'AB', 'O'])->nullable();
            $table->enum('rhesus', ['POSITIF', 'NEGATIF'])->nullable();
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('telephone')->nullable();
            $table->text('address')->nullable();
            $table->foreignId('province_id')->nullable()->constrained('indonesia_provinces');
            $table->foreignId('city_id')->nullable()->constrained('indonesia_cities');
            $table->foreignId('district_id')->nullable()->constrained('indonesia_districts');
            $table->foreignId('village_id')->nullable()->constrained('indonesia_villages');
            $table->string('postal_code')->nullable();
            $table->string('rt_rw')->nullable();
            $table->string('occupation')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('profiles');
    }
};
