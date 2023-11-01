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
        return response()->json($menu_table);

    }

    public function byKategori($id_kategori)
    {
        $menu_table = Menu::where('id_kategori_menu', $id_kategori)->get();
        return response()->json($menu_table);
    }


    public function byIdMenu($id_menu)
    {
        $menu_table = Menu::where('id_menu', $id_menu)->get();
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
}
