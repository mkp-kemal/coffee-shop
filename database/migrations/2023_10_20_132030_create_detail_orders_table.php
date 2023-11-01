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
        Schema::create('detail_orders', function (Blueprint $table) {
            $table->increments("id_detail_order");
            $table->integer("id_order")->unsigned();
            $table->integer("id_menu")->unsigned();
            $table->integer("id_varian_menu")->unsigned()->nullable();
            $table->integer("jumlah_beli");
            $table->integer("harga_beli");
            $table->foreign('id_order')->references('id_order')->on('orders')->onDelete("cascade");
            $table->foreign('id_menu')->references('id_menu')->on('menu')->onDelete("cascade");
            $table->foreign('id_varian_menu')->references('id_varian_menu')->on('varian_menu')->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_orders');
    }
};
