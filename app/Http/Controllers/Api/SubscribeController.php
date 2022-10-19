<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscriber;

class SubscribeController extends Controller
{
    public function subscribe(Request $request){

        $validation =   $request->validate([
            'email' => 'required|email:rfc,dns|unique:subscribers'
        ]);
        if($request->email == null){
            return response()->json([
                'msg' => 'Type You Email!',
                'status' => 400,
            ]);
        }else{
            $check              =   Subscriber::where('email','=',$request->email)->first();
            if($check){
                return response()->json([
                    'msg' => 'Email Already Subscribe to eBaskat!',
                    'status' => 400,
                ]);
            }else{
                $subscribe          =   new Subscriber();
                $subscribe->email   =   $request->email;
                $subscribe->save();
                $details = [
                    'voucher'   => 'ebaskat10',
                    'email'     =>  $request->email
                ];
                \Mail::to($request->email)->send(new \App\Mail\SubscribeMail($details));
                return true;
            }
        }
    }
}
