<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $primaryKey = "id_menu";

    public function kategori_menu()
    {
        return $this->belongsTo(KategoriMenu::class);
    }

    public function varian_menu()
    {
        return $this->hasMany(VarianMenu::class);
    }
}
