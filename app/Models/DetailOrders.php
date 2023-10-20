<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailOrders extends Model
{
    use HasFactory;
    protected $primaryKey = "id_detail_order";

    public function orders():
    {
        return $this->belongsTo(Orders::class);
    }
    public function menu():
    {
        return $this->belongsTo(Menu::class);
    }
    public function varian_menu():
    {
        return $this->belongsTo(VarianMenu::class);
    }

}
