<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonorQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('donor_questions', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // 'today', 'week', '6weeks', etc.
            $table->text('question');
            $table->enum('type', ['yes_no', 'text', 'number'])->default('yes_no');
            $table->boolean('is_disqualifying')->default(false); // Jika ya = diskualifikasi
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('donor_questions');
    }
}
