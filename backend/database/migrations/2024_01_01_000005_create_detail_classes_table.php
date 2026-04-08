<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_classes', function (Blueprint $table) {
            $table->id();
            $table->integer('Slot')->nullable();
            $table->foreignId('StudentId')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('ClassId')->nullable()->constrained('classes')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_classes');
    }
};
