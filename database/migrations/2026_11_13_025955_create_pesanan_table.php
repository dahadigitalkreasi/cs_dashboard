<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {

            $table->id();

            // IDENTITAS PESANAN
            $table->string('id_pesanan', 100)->unique();
            $table->dateTime('tgl_chat_masuk')->nullable();
            $table->dateTime('tgl_order')->nullable();
            $table->string('cs', 100)->nullable();

            // DATA PEMBELI
            $table->string('nama_pembeli')->nullable();
            $table->string('no_hp', 30)->nullable();
            $table->text('alamat')->nullable();

            // PENGIRIMAN
            $table->string('no_resi')->nullable();
            $table->string('status', 100)->nullable();
            $table->string('ekspedisi', 100)->nullable();

            // PEMBAYARAN
            $table->string('pembayaran', 50)->nullable();
            $table->decimal('nominal_dp', 15, 2)->nullable();
            $table->decimal('nominal_non_cod', 15, 2)->nullable();
            $table->decimal('nominal_cod', 15, 2)->nullable();

            // PRODUK
            $table->string('kategori')->nullable();
            $table->unsignedInteger('qty')->nullable();
            $table->text('notes')->nullable();

            // KEUANGAN
            $table->decimal('harga', 15, 2)->nullable();
            $table->decimal('hpp', 15, 2)->nullable();
            $table->decimal('ongkir', 15, 2)->nullable();
            $table->decimal('fee_cod', 15, 2)->nullable();
            $table->decimal('iklan', 15, 2)->nullable();
            $table->decimal('keuntungan', 15, 2)->nullable();

            // TAMBAHAN
            $table->text('catatan_internal')->nullable();

            $table->timestamps();

            // INDEX
            $table->index('tgl_order');
            $table->index('cs');
            $table->index('status');
            $table->index('kategori');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
