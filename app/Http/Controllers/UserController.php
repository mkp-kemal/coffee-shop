<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $data = Users::select("username","role");

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
}
