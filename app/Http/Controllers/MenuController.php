<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;

class MenuController extends Controller
{
    public function get_all(Request $request){

        $limit      = $request->input("limit");
        $offset     = $request->input("offset");
        $keyword    = $request->input("search");
        $order      = $request->input("order");

        $data = Menu::select("id_menu as id","nama_menu","harga_menu","deskripsi_menu","url_gambar","nama_kategori_menu","menu.id_kategori_menu");
        $data->join("kategori_menu",'menu.id_kategori_menu',"=","kategori_menu.id_kategori_menu");

        if(!empty($keyword)){
            $data->where(function($query) use ($keyword) {
                $query->where("nama_menu","like","%$keyword%");
                $query->orWhere("harga_menu","like","%$keyword%");
                $query->orWhere("deskripsi_menu","like","%$keyword%");
                $query->orWhere("nama_kategori_menu","like","%$keyword%");
            });
        }

        $total_data    = $data->count();

        if(!empty($order)){
            $data->orderBy($order[0],$order[1]);
        }

        $data->limit($limit);
        $data->offset($offset);

        $result     = $data->get();
        foreach($result as $key => $row){
            $row->harga_menu    = "Rp".(str_replace(",",".",number_format($row->harga_menu)));
            if(!empty($row->url_gambar)){
                if(strpos($row->url_gambar,"http") !== 0){
                    $row->url_gambar = url($row->url_gambar);
                }
            }
        }

        return response([
            "data"      => $result,
            "totalData" => $total_data,
        ]);
    }

    public function insert(Request $request){
        
        $user       = Auth::user();
        if($user->role !== "admin"){
            return response(["unauthorized"],401);
        }

        $request->validate([
            "id_kategori_menu" => "required",
            "nama_menu" => "required",
            "harga_menu" => "required",
            "deskripsi_menu" => "required",
        ]);

        Validator::validate($request->file(), [
            'gambar' => [
                'required',
                File::image()
                    ->max('2mb'),
            ],
        ]);
        

        $menu   = new Menu();
        $menu->id_kategori_menu     = $request->input("id_kategori_menu");
        $menu->nama_menu     = $request->input("nama_menu");
        $menu->harga_menu     = preg_replace('/[^0-9]/','',$request->input("harga_menu"));
        $menu->deskripsi_menu     = $request->input("deskripsi_menu");

        if(!empty($request->file("gambar"))){
            $file           = $request->file("gambar");
            $filename       = "menu_".time().".".$file->getClientOriginalExtension();
            $pathfile       = "/gambar_menu/";
            if($file->move($_SERVER["DOCUMENT_ROOT"].$pathfile,$filename)){
                $menu->url_gambar     = $pathfile.$filename;
            }
        }

        if($menu->save()){
            $data   = [
                "message"   => "Successfuly create menu"
            ];
            return response()->json($data);
        }else{
            $data   = [
                "message"   => "Failed, please try again"
            ];
            return response()->json($data,422);
        }
    }

    public function update(Request $request,$id_menu){
        
        $user       = Auth::user();
        if($user->role !== "admin"){
            return response(["unauthorized"],401);
        }

        $request->validate([
            "id_kategori_menu" => "required",
            "nama_menu" => "required",
            "harga_menu" => "required",
            "deskripsi_menu" => "required",
        ]);
        

        $menu   = Menu::find($id_menu);
        $menu->id_kategori_menu     = $request->input("id_kategori_menu");
        $menu->nama_menu     = $request->input("nama_menu");
        $menu->harga_menu     = preg_replace('/[^0-9]/','',$request->input("harga_menu"));
        $menu->deskripsi_menu     = $request->input("deskripsi_menu");

        if(!empty($request->file("gambar"))){
            Validator::validate($request->file(), [
                'gambar' => [
                    'required',
                    File::image()
                        ->max('2mb'),
                ],
            ]);

            $file           = $request->file("gambar");
            $filename       = "menu_".time().".".$file->getClientOriginalExtension();
            $pathfile       = "/gambar_menu/";
            if($file->move($_SERVER["DOCUMENT_ROOT"].$pathfile,$filename)){
                $menu->url_gambar     = $pathfile.$filename;
            }
        }

        if($menu->save()){
            $data   = [
                "message"   => "Successfuly update menu"
            ];
            return response()->json($data);
        }else{
            $data   = [
                "message"   => "Failed, please try again"
            ];
            return response()->json($data,422);
        }
    }

    public function delete($id_menu){
        
        $user       = Auth::user();
        if($user->role !== "admin"){
            return response(["unauthorized"],401);
        }

        $menu   = Menu::find($id_menu);
        if($menu->delete()){
            $data   = [
                "message"   => "Successfuly delete menu"
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
