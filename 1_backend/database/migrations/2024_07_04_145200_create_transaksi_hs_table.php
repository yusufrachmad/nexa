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
        Schema::create('transaksi_hs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_customer')->constrained(table: 'ms_customers', indexName: 'id_customer')->cascadeOnDelete();
            $table->string('nomor_transaksi');
            $table->date('tanggal_transaksi');
            $table->integer('total_transaksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_hs');
    }
};
