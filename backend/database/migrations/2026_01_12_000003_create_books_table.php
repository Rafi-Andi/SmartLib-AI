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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('isbn')->nullable();
            $table->string('title');
            $table->string('author')->nullable();
            $table->string('publisher')->nullable();
            $table->year('year_published')->nullable();
            $table->string('cover_img')->nullable();
            $table->text('summary')->nullable();
            $table->string('category')->nullable();
            $table->string('location')->nullable(); // Lokasi rak buku
            $table->integer('stock_count')->default(1);
            $table->integer('available_count')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Index untuk pencarian cepat
            $table->index('isbn');
            $table->index('title');
            $table->index(['school_id', 'isbn']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
