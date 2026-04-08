<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('ClassName', 255)->nullable();
            $table->string('RackName', 255)->nullable();
            $table->string('RackSlot', 255)->nullable();
            $table->integer('SlotTotal')->nullable();
            $table->string('QrCode', 255)->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('ClassId')
                ->references('id')
                ->on('classes')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['ClassId']);
        });

        Schema::dropIfExists('classes');
    }
};
