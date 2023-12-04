<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function get_all(Request $request){
        $user       = Auth::user();
        if($user->role !== "admin"){
            return response(["unauthorized"],401);
        }

        $limit      = $request->input("limit");
        $offset     = $request->input("offset");
        $keyword    = $request->input("search");
        $order      = $request->input("order");

        $data = User::select("id_user as id","username","role");

        if(!empty($keyword)){
            $data->where(function($query) use ($keyword) {
                $query->where("username","like","%$keyword%");
                $query->orWhere("role","like","%$keyword%");
            });
        }

        $total_data    = $data->count();

        if(!empty($order)){
            $data->orderBy($order[0],$order[1]);
        }

        $data->limit($limit);
        $data->offset($offset);

        return response([
            "data"      => $data->get(),
            "totalData" => $total_data,
        ]);
    }

    public function insert(Request $request){
        $request->validate([
            "username" => "required",
            "password" => "required|min:8",
            "role" => "required|in:admin,kasir",
        ]);

        $user   = new User();
        $user->username     = $request->input("username");
        $user->password     = Hash::make($request->input("password"));
        $user->role         = $request->input("role");

        if($user->save()){
            $data   = [
                "message"   => "Successfuly create user"
            ];
            return response()->json($data);
        }else{
            $data   = [
                "message"   => "Failed, please try again"
            ];
            return response()->json($data,422);
        }
    }

    public function update(Request $request,$id_user){
        $request->validate([
            "username" => "required",
            "role" => "required|in:admin,kasir",
        ]);
        if(!empty($request->input("password"))){
            $request->validate([
                "password" => "required:min:8",
            ]);
        }

        $user   = User::find($id_user);
        $user->username     = $request->input("username");
        if(!empty($request->input("password"))){
            $user->password     = Hash::make($request->input("password"));
        }
        $user->role         = $request->input("role");

        if($user->save()){
            $data   = [
                "message"   => "Successfuly update user"
            ];
            return response()->json($data);
        }else{
            $data   = [
                "message"   => "Failed, please try again"
            ];
            return response()->json($data,422);
        }
    }

    public function delete($id_user){

        $user   = User::find($id_user);
        if($user->delete()){
            $data   = [
                "message"   => "Successfuly delete user"
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
