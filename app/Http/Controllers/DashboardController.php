<?php

namespace App\Http\Controllers;

use App\Models\DetailOrders;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function get_data_dashboard() {
        
        $total_menu     = Menu::select(DB::raw(' COUNT(1) as total '))->value('total');
        $total_user     = User::select(DB::raw(' COUNT(1) as total '))->value('total');


        $menu_favorit   = DetailOrders::select(DB::raw(" SUM(jumlah_beli) as total_beli "),"id_menu")
        ->with(["menu"])
        ->groupBy("id_menu")
        ->orderBy("total_beli","DESC")
        ->limit(5)
        ->get();

        $date_arr   = [];
        $data_charts    = [];

        for($i = 6;$i >= 0;$i--){
            $date   = date("Y-m-d",strtotime("-$i day"));
            $date_arr[]  = $date;

            $data_charts[$date]     = 0;
        }

        $get_data_chart  = DetailOrders::select(
            DB::raw(" SUM(IF(DATE(created_at),(harga_beli * jumlah_beli),0)) as total_pembelian "),
            DB::raw("DATE(created_at) as tanggal")
        )->whereIn(DB::raw("DATE(created_at)"),$date_arr)
        ->groupBy("tanggal")
        ->orderBy("tanggal")
        ->get();

        foreach($get_data_chart as $row){
            $data_charts[$row->tanggal]    = intval($row->total_pembelian);
        }

        $data_charts    = array_values($data_charts);

        return response()->json([
            "total_menu"    => $total_menu,
            "total_user"    => $total_user,
            "menu_favorit"  => $menu_favorit,
            "chart"         => $data_charts,
            "date_arr"      => $date_arr,
        ]);
    }
}
