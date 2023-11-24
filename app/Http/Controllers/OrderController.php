<?php

namespace App\Http\Controllers;

use App\Models\DetailOrders;
use App\Models\DeviceLogs;
use App\Models\Menu;
use App\Models\Orders;
use App\Models\VarianMenu;
use Illuminate\Http\Request;
use Xendit\Configuration;
use Xendit\PaymentRequest\PaymentRequestApi;
use Xendit\PaymentRequest\PaymentRequestParameters;

class OrderController extends Controller
{
    public function orderByInvoice($nomor_invoice){
        $data   = Orders::where("nomor_invoice",$nomor_invoice)
        ->with(["detail_order" => function($q){
            $q->with(["menu"]);
        }])->first();

        $total_price        = 0;

        foreach($data->detail_order as $detail_order){
            $total_price += ($detail_order->harga_beli * $detail_order->jumlah_beli);
        }

        $data->total_price = $total_price;

        return response()->json($data);
    }

    public function orders(Request $request){
        $request->validate([
            "nama_pemesan"  => "required",
            "no_wa_pemesan"  => "required|numeric",
            "no_meja"  => "required",
            "jenis_pembayaran"  => "required|in:DANA,SHOPEEPAY,TUNAI",
            "menu.*" => "required",
            "device_name"  => "required",
        ]);

        $nomor_invoice  = date("YmdHis") . "-" . $request->no_meja ."-". rand(100,999);

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
        $orders->status_pembayaran  = "pending";

        if($orders->save()){

            $total_price    = 0;

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

                    $total_price += ($detail_orders->harga_beli * $detail_orders->jumlah_beli);

                }
            }
            
            if($orders->jenis_pembayaran !== "TUNAI"){
                $url_redirect = $this->payment($nomor_invoice,$total_price,$orders->jenis_pembayaran);
            }else{
                $url_redirect   = env("SANCTUM_STATEFUL_DOMAINS")."/invoices?no=".$nomor_invoice;
            }

            $device_logs    = new DeviceLogs;
            $device_logs->device_name = $request->input("device_name");
            $device_logs->save();

            return response()->json([
                "message" => "Order berhasil",
                "invoice" => $orders->nomor_invoice,
                "url_redirect"  => $url_redirect
            ]);
        }else{
            return response()->json([
                "message" => "Order gagal"
            ]);
        }
    }

    public function payment($nomor_invoice,$amount,$type){
        Configuration::setXenditKey(env("XENDIT_KEY_DEV"));

        $apiInstance = new PaymentRequestApi();
        $idempotency_key = ""; // string
        $for_user_id = ""; // string
        $payment_request_parameters = new PaymentRequestParameters([
            'reference_id' => $nomor_invoice,
            'amount' => $amount,
            'currency' => 'IDR',
            'country' => 'ID',
            'payment_method' => [
                'type' => 'EWALLET',
                'ewallet' => [
                    'channel_code' => $type,
                    'channel_properties' => [
                        'success_return_url' => url("/payment_callback?ref_id=$nomor_invoice")
                    ]
                ],
                'reusability' => 'ONE_TIME_USE'
            ]
        ]);

        try {
            $result = $apiInstance->createPaymentRequest($idempotency_key, $for_user_id, $payment_request_parameters);
            return $result["actions"][0]["url"];
        } catch (\Xendit\XenditSdkException $e) {
            echo 'Exception when calling PaymentRequestApi->createPaymentRequest: ', $e->getMessage(), PHP_EOL;
            echo 'Full Error: ', json_encode($e->getFullError()), PHP_EOL;
        }
    }

    public function paymentCallback(Request $req){

        Configuration::setXenditKey(env("XENDIT_KEY_DEV"));

        $apiInstance = new PaymentRequestApi();
        $for_user_id = ""; // string
        $ref_id         = $req->input("ref_id");
        $reference_ids = array($ref_id); // string[]
        try {
            $result = $apiInstance->getAllPaymentRequests($for_user_id, $reference_ids);

            $status     = $result["data"][0]["status"];
            if($status === "SUCCEEDED"){
                $orders     = Orders::where("nomor_invoice",$ref_id)->first();
                if(!empty($orders)){
                    $orders->status_pembayaran = "success";
                    $orders->save();
                }
            }
            return redirect(env("SANCTUM_STATEFUL_DOMAINS")."/invoices?no=".$ref_id);
        } catch (\Xendit\XenditSdkException $e) {
            echo 'Exception when calling PaymentRequestApi->getAllPaymentRequests: ', $e->getMessage(), PHP_EOL;
            echo 'Full Error: ', json_encode($e->getFullError()), PHP_EOL;
        }
        
    }
}
