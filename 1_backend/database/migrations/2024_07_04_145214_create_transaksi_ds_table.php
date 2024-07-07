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
        Schema::create('transaksi_ds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_transaksi_hs')->constrained(table: 'transaksi_hs', indexName: 'id_transaksi_hs')->cascadeOnDelete();
            $table->string('kd_barang');
            $table->string('nama_barang');
            $table->integer('qty');
            $table->integer('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_ds');
    }
};
