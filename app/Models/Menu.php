<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $primaryKey = "id_menu";
    protected $table = 'menu';

    public function kategori_menu()
    {
        return $this->belongsTo(KategoriMenu::class,"id_kategori_menu","id_kategori_menu");
    }

    public function varian_menu()
    {
        return $this->hasMany(VarianMenu::class,"id_varian_menu","id_varian_menu");
    }
}
