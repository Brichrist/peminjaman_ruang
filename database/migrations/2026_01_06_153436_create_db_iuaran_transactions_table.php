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
        Schema::create('db_iuaran_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->time('jam');
            $table->string('dari', 255);
            $table->text('keterangan')->nullable();
            $table->decimal('nominal', 15, 2);
            $table->string('bukti_foto', 255)->nullable();
            $table->timestamps();

            $table->index('tanggal');
            $table->index('dari');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('db_iuaran_transactions');
    }
};
