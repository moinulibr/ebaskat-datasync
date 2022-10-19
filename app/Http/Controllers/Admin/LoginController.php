<?php

namespace App\Http\Controllers\Admin;

use App\Classes\EBasketMailer;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => ['logout']]);
    }

    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request): JsonResponse
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->filled('remember'))) {
            return response()->json(route('admin.dashboard'));
        }

        return response()->json(array('errors' => [0 => 'Credentials Doesn\'t Match !']));
    }

    public function showForgotForm()
    {
        return view('admin.forgot');
    }

    public function forgot(Request $request): JsonResponse
    {
        if (Admin::where('email', '=', $request->email)->count() > 0) {
            $admin = Admin::where('email', '=', $request->email)->firstOrFail();
            $token = md5(time() . $admin->name . $admin->email);

            $admin->token = $token;
            $admin->save();

            $subject = "Reset Password Request";
            $msg = "Please click this link : " . '<a href="' . route('admin.change.token', $token) . '">' . route('admin.change.token', $token) . '</a>' . ' to change your password.';
            
                $data = [
                    'to' => $request->email,
                    'subject' => $subject,
                    'body' => $msg,
                ];
                $mailer = new EBasketMailer();
                $mailer->sendCustomMail($data);
            
            return response()->json('Verification Link Sent Successfully!. Please Check your email.');
        } else {
            return response()->json(array('errors' => [0 => 'No Account Found With This Email.']));
        }
    }

    public function showChangePassForm($token)
    {
        if (empty($token)) {
            return redirect()->route('admin.login');
        }
        
        $row = Admin::where('token', $token)->first();
        if($row)
        {
            return view('admin.change-password', compact('token'));
        }
        return redirect()->route('admin.login')->with('errors', [0 => 'Link is invalid.']);
    }

    public function changePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'newPassword' => 'min:6',
            'confirmNewPassword' => 'min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        if ($request->newPassword != $request->confirmNewPassword) 
        {
            return response()->json(array('errors' => [0 => 'Confirm password does not match.']));
        }

        $admin = Admin::where('token', $request->token)->first();
        if($admin)
        {
            $admin->password = Hash::make($request->newPassword);
            $admin->token = null;                
            $admin->save();
            return response()->json('Successfully changed your password.');
        }
        else{
            return response()->json(array('errors' => [0 => 'Link is invalid.']));
        }
    }


    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin');
    }
}
