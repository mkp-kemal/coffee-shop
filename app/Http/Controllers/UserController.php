<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function get_all(Request $request){
        $limit      = $request->input("limit");
        $offset     = $request->input("offset");
        $keyword    = $request->input("search");

        $user = Users::select("username","role");

        if(!empty($keyword)){
            $user->where(function($query) use ($keyword) {
                $query->where("username","like","%$keyword%");
                $query->orWhere("role","like","%$keyword%");
            });
        }

        $total_data    = $user->count();

        $user->limit($limit);
        $user->offset($offset);

        return [
            "data"      => $user->get(),
            "totalData" => $total_data,
        ];
    }
}
