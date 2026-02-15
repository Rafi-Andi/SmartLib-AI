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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('school_id')->nullable()->after('id')->constrained('schools')->onDelete('cascade');
            $table->string('nisn')->nullable()->after('name');
            $table->string('rfid_uid')->nullable()->after('nisn');
            $table->enum('role', ['superadmin', 'admin', 'student'])->default('student')->after('rfid_uid');
            $table->string('avatar')->nullable()->after('role');
            $table->boolean('is_active')->default(true)->after('avatar');
            
            // Index untuk pencarian cepat
            $table->index('rfid_uid');
            $table->index('nisn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn(['school_id', 'nisn', 'rfid_uid', 'role', 'avatar', 'is_active']);
        });
    }
};
