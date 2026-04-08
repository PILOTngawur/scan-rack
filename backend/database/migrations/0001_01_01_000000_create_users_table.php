<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('FullName', 255)->nullable();
            $table->integer('NISNUPTK')->nullable();
            $table->string('email', 255)->nullable()->unique();
            $table->string('password', 255);
            $table->string('Role', 255)->nullable(); // 'admin' or 'student'
            $table->string('DeviceName', 255)->nullable();
            $table->unsignedBigInteger('ClassId')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
