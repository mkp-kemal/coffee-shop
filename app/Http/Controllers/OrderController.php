<?php

namespace App\Http\Controllers;

use App\Models\DetailOrders;
use App\Models\DeviceLogs;
use App\Models\Menu;
use App\Models\Orders;
use App\Models\VarianMenu;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function orderByWa(Request $request){
        $request->validate([
            "no_wa_pemesan"  => "required|numeric",
        ]);

        $data   = Orders::where($request->input("no_wa_pemesan"),"no_wa_pemesan")
        ->with(["detail_order"])->get();

        return response()->json($data);
    }

    public function orders(Request $request){
        $request->validate([
            "nama_pemesan"  => "required",
            "no_wa_pemesan"  => "required|numeric",
            "no_meja"  => "required",
            "jenis_pembayaran"  => "required",
            "status_pembayaran"  => "required",
            "menu.*" => "required",
            "device_name"  => "required",
        ]);

        $nomor_invoice  = date("YmdHis") . $request->no_meja;

        $no_wa_pemesan  = $request->input("no_wa_pemesan");

        if(!empty($no_wa_pemesan)){
            $no_wa_pemesan  = preg_replace("/^0/","62",$no_wa_pemesan);
        }

        $orders     = new Orders;
        $orders->nomor_invoice      = $nomor_invoice;
        $orders->nama_pemesan       = $request->input("nama_pemesan");
        $orders->no_meja            = $request->input("no_meja");
        $orders->no_wa_pemesan      = $no_wa_pemesan;
        $orders->jenis_pembayaran   = $request->input("jenis_pembayaran");
        $orders->status_pembayaran  = $request->input("status_pembayaran");

        if($orders->save()){

            foreach($request->input("menu") as $menu){
                $menu      = (object) $menu;
                $product   = Menu::find($menu->id_menu);
                if(!empty($menu->id_varian_menu)){
                    $varian    = VarianMenu::where("id_menu",$menu->id_menu)
                    ->where("id_varian_menu",$menu->id_varian_menu)->first();
                }
                if(!empty($product)){
                    $detail_orders              = new DetailOrders;
                    $detail_orders->id_order    = $orders->id_order;
                    $detail_orders->id_menu     = $menu->id_menu;
                    $detail_orders->id_varian_menu  = !empty($varian->id_varian_menu) ? $varian->id_varian_menu : null;
                    $detail_orders->jumlah_beli = $menu->qty;
                    $detail_orders->harga_beli  = $product->harga_menu;

                    if(!empty($varian->harga_varian_menu)){
                        $detail_orders->harga_beli += $varian->harga_varian_menu;
                    }

                    $detail_orders->save();

                }
            }

            $device_logs    = new DeviceLogs;
            $device_logs->device_name = $request->input("device_name");
            $device_logs->save();

            return response()->json([
                "message" => "Order berhasil"
            ]);
        }else{
            return response()->json([
                "message" => "Order gagal"
            ]);
        }
    }
}
