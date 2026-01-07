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
        Schema::create('murids', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nis')->unique();
            $table->string('nama');
            $table->string('photo')->nullable();
            $table->string('alamat')->nullable();
            $table->foreignId('jenjang_id')->constrained();
            $table->foreignId('kelas_id')->constrained();
            $table->foreignId('tahun_id')->constrained();
            $table->foreignId('sekolah_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('murids');
    }
};
