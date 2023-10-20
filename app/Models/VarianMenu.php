<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VarianMenu extends Model
{
    use HasFactory;
    protected $primaryKey = "id_varian_menu";
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
    public function detail_orders(){
        return $this->hasMany(DetailOrders::class);
    }

}
