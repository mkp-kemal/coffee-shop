<?php

namespace App\Http\Controllers;

use App\Models\KategoriMenu;
use App\Models\Menu;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function productAll()
    {
        $menu_table = KategoriMenu::with(["menu"])->get();
        foreach($menu_table as $key => $row){
            if(!empty($row->menu)){
                foreach($row->menu as $menu){
                    if(!empty($menu->url_gambar)){
                        if(strpos($menu->url_gambar,"http") !== 0){
                            $menu->url_gambar = url($menu->url_gambar);
                        }
                    }
                }
            }

            $menu_table[$key]   = $row;
        }
        return response()->json($menu_table);
    }

    public function categoryProductAll()
    {
        $menu_table = KategoriMenu::all();
        return response()->json($menu_table);
    }

    public function byKategori($id_kategori)
    {
        $menu_table = Menu::where('id_kategori_menu', $id_kategori)->get();
        return response()->json($menu_table);
    }

    public function byIdMenu($id_menu)
    {
        $menu_table = Menu::where('id_menu', $id_menu)->first();
        return response()->json($menu_table);
    }

    public function pluckToName($id_menu)
    {
        $menu_table = Menu::all();
        $menu_id_2 = $menu_table->where('id_menu', $id_menu)->pluck('nama_menu');
        return response()->json($menu_id_2);
    }

    public function joinTable()
    {
        // JOIN TABLE
        $menu_table = Menu::select('menu.nama_menu', 'kategori_menu.nama_kategori_menu', 'menu.harga_menu')
            ->join('kategori_menu', 'menu.id_kategori_menu', '=', 'kategori_menu.id_kategori_menu')
            ->get();
        return response()->json($menu_table);
    }

    public function orderDetails(Request $request){
        $cart       = $request->input("cart");
        $menu_ids   = [];
        foreach($cart as $menu){
            $menu_ids[]     = $menu["id_menu"];
        }

        $data_menu   = Menu::select("id_menu","nama_menu","harga_menu")->whereIn("id_menu",$menu_ids)->get();

        $total_price    = 0;
        foreach($data_menu as $row){
            $idx = array_search($row->id_menu, array_column($cart, 'id_menu'));
            $row->qty   = $cart[$idx]["qty"];

            $total_price    += ($row->qty * $row->harga_menu);
        }

        $result     = [
            "total_price"   => $total_price,
            "cart"          => $data_menu
        ];

        return response()->json($result);
    }
}
