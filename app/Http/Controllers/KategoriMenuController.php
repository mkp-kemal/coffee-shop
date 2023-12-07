<?php

namespace App\Http\Controllers;

use App\Models\KategoriMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriMenuController extends Controller
{
    public function get_all(Request $request){

        $limit      = $request->input("limit");
        $offset     = $request->input("offset");
        $keyword    = $request->input("search");
        $order      = $request->input("order");

        $data = KategoriMenu::select("id_kategori_menu as id","nama_kategori_menu");

        if(!empty($keyword)){
            $data->where(function($query) use ($keyword) {
                $query->where("nama_kategori_menu","like","%$keyword%");
            });
        }

        $total_data    = $data->count();

        if(!empty($order)){
            $data->orderBy($order[0],$order[1]);
        }

        if(!empty($limit)){
            $data->limit($limit);
            $data->offset($offset);
        }

        return response([
            "data"      => $data->get(),
            "totalData" => $total_data,
        ]);
    }

    public function insert(Request $request){
        
        $user       = Auth::user();
        if($user->role !== "admin"){
            return response(["unauthorized"],401);
        }

        $request->validate([
            "nama_kategori_menu" => "required",
        ]);

        $kategori_menu   = new KategoriMenu();
        $kategori_menu->nama_kategori_menu     = $request->input("nama_kategori_menu");

        if($kategori_menu->save()){
            $data   = [
                "message"   => "Successfuly create kategori menu"
            ];
            return response()->json($data);
        }else{
            $data   = [
                "message"   => "Failed, please try again"
            ];
            return response()->json($data,422);
        }
    }

    public function update(Request $request,$id_kategori_menu){
        
        $user       = Auth::user();
        if($user->role !== "admin"){
            return response(["unauthorized"],401);
        }

        $request->validate([
            "nama_kategori_menu" => "required",
        ]);

        $kategori_menu   = KategoriMenu::find($id_kategori_menu);
        $kategori_menu->nama_kategori_menu     = $request->input("nama_kategori_menu");

        if($kategori_menu->save()){
            $data   = [
                "message"   => "Successfuly update kategori menu"
            ];
            return response()->json($data);
        }else{
            $data   = [
                "message"   => "Failed, please try again"
            ];
            return response()->json($data,422);
        }
    }

    public function delete($id_kategori_menu){
        
        $user       = Auth::user();
        if($user->role !== "admin"){
            return response(["unauthorized"],401);
        }

        $kategori_menu   = KategoriMenu::find($id_kategori_menu);
        if($kategori_menu->delete()){
            $data   = [
                "message"   => "Successfuly delete kategori menu"
            ];
            return response()->json($data);
        }else{
            $data   = [
                "message"   => "Failed, please try again"
            ];
            return response()->json($data,422);
        }
    }
}
