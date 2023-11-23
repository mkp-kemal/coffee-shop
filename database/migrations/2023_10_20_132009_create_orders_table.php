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
        Schema::create('orders', function (Blueprint $table) {
            $table->increments("id_order");
            $table->string("nomor_invoice",100);
            $table->string("nama_pemesan",100);
            $table->string("no_wa_pemesan",100);
            $table->string("no_meja",100);
            $table->string("jenis_pembayaran",100)->nullable();
            $table->enum("status_pembayaran",['pending','success','failed']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
