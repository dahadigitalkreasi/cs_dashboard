<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iklan_harian', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('cs', 100);
            $table->string('produk', 255);
            $table->unsignedInteger('lead_meta')->nullable();
            $table->unsignedInteger('lead_cs')->nullable();
            $table->decimal('spend_iklan', 15, 2)->nullable();
            $table->timestamps();

            // Import ulang hari + CS + produk memperbarui, tidak menggandakan.
            $table->unique(['tanggal', 'cs', 'produk'], 'iklan_harian_unique');
            $table->index(['cs', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iklan_harian');
    }
};
