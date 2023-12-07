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
        Schema::create('varian_menu', function (Blueprint $table) {
            $table->increments("id_varian_menu");
            $table->integer("id_menu")->unsigned();
            $table->string("nama_varian_menu",100);
            $table->integer("harga_varian_menu");
            $table->foreign('id_menu')->references('id_menu')->on('menu')->onDelete("cascade");
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('varian_menu');
    }
};
