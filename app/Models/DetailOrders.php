<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailOrders extends Model
{
    use HasFactory;
    protected $primaryKey = "id_detail_order";

    public function orders()
    {
        return $this->belongsTo(Orders::class,"id_order","id_order");
    }
    public function menu()
    {
        return $this->belongsTo(Menu::class,"id_menu","id_menu");
    }
    public function varian_menu()
    {
        return $this->belongsTo(VarianMenu::class,"id_varian_menu","id_varian_menu");
    }

}
