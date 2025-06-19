<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->string('doctor_id', 10)->primary(); // D001, D002, dst
            $table->string('nama');
            $table->string('spesialisasi');
            $table->string('hari');
            $table->time('mulai_praktek');
            $table->time('selesai_praktek');
            $table->string('foto')->nullable(); // <--- tambahkan ini
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
