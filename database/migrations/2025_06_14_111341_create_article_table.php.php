<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_articles_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            
            // Author information
            $table->string('author');
            $table->string('author_title')->nullable();
            $table->string('author_avatar')->nullable();
            
            // Status and features
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->string('category');
            
            // Timestamps
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            // Additional fields
            $table->json('tags')->nullable();
            $table->unsignedInteger('views')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('articles');
    }
};
