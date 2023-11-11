<?php

namespace App\Http\Controllers;

use App\Models\DeviceLogs;
use Illuminate\Http\Request;

class DeviceLogsController extends Controller
{
    public function insert(Request $request){
        $request->validate([
            "device_name"  => "required",
        ]);

        $device_logs    = new DeviceLogs;
        $device_logs->device_name = $request->input("device_name");

        if($device_logs->save()){
            return response()->json([
                "message" => "Successfuly insert"
            ])
        }else{
            return response()->json([
                "message" => "Failed please try again"
            ])
        }
    }
}
