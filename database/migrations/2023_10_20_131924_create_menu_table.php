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
        Schema::create('menu', function (Blueprint $table) {
            $table->increments("id_menu");
            $table->integer("id_kategori_menu")->unsigned();
            $table->string("nama_menu",100);
            $table->text("deskripsi_menu");
            $table->string("harga_menu");
            $table->string("url_gambar");
            $table->foreign('id_kategori_menu')->references('id_kategori_menu')->on('kategori_menu')->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
