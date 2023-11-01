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
        return $this->belongsTo(Menu::class,"id_menu","id_menu");
    }

    public function detail_orders()
    {
        return $this->hasMany(DetailOrders::class,"id_detail_order","id_detail_order");
    }

}
