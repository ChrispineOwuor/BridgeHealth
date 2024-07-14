<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\MakeOtpRequest;
use App\Http\Requests\NewPassRequest;
use App\Http\Requests\OtpVerificationRequest;
use App\Http\Requests\RegisterAdmRequest;
use App\Http\Requests\RegisterDocRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\Admins;
use App\Models\Doctors;
use App\Models\Otp;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(Auth::user());
        return response()->json(['user' => $user, "msg" => "success", Response::HTTP_ACCEPTED]);
    }

    public function register(RegisterUserRequest $request)
    {
        $user = User::create([
            "email" => $request->email,
            "name" => $request->name,
            "password" => Hash::make($request->password),

        ]);
        Patient::create([
            'user_id' => $user->id,
        ]);
        return response()->json(["msg" => "success", Response::HTTP_ACCEPTED]);
    }

    public function registerDoc(RegisterDocRequest $request)
    {
        $user = User::create([
            "email" => $request->email,
            "name" => $request->name,
            "password" => Hash::make($request->password),
            "role" => "doctor"


        ]);
        Doctors::create([
            'user_id' => $user->id,
        ]);


        return response()->json(["msg" => "success", Response::HTTP_ACCEPTED]);
    }
    public function registerAdm(RegisterAdmRequest $request)
    {
        $user = User::create([
            "email" => $request->email,
            "name" => $request->name,
            "password" => Hash::make($request->password),
            "role" => "admin"


        ]);
        Admins::create([
            'user_id' => $user->id,
        ]);


        return response()->json(["msg" => "success", Response::HTTP_ACCEPTED]);;
    }

    public function login(LoginUserRequest $request)
    {
        if (!Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            return response()->json(["msg" => "invalid credentials"], Response::HTTP_FORBIDDEN);
        }
        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('API TOKEN')->plainTextToken;
        return response()->json(["user" => $user, "token" => $token], Response::HTTP_ACCEPTED);
    }
    public function logout(Request $request)
    {
        auth()->user()->tokens->each(function ($token) {
            $token->delete();
        });
        return response()->json(["status" => "success", "message" => "Successfully logged out"], Response::HTTP_OK);

    }
    public function requestOtp(MakeOtpRequest $request)
    {
        $code = random_int(1000, 9999);
        $token = bin2hex(random_bytes(8));
        $email = $request->email;

        $otp =  Otp::create([
            "email" => $email,
            "token" => $token,
            "code" => $code,
        ]);
        //send mail

        $cookie = cookie('recovery_token', $token, 60 * 2, null, null, false, true);
        return \Illuminate\Support\Facades\Response::json(["status" => "success", "msg" => "Sent verification link"])->withCookie($cookie);
    }
    public function verifyOtp(OtpVerificationRequest $request)
    {
        $token = $request->cookie('recovery_token');
        $code = $request->input('code');

        $otp = Otp::where('token', $token)->where('code', $code)->first();

        if (!$otp) {
            return response()->json([
                'msg' => 'Invalid token or code. Please try again.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'msg' => 'OTP verified successfully.', Response::HTTP_ACCEPTED
        ]);
    }

    public function newPswd(NewPassRequest $request)
    {
        $token = $request->cookie('recovery_token');
        $otp = Otp::where('token', $token)->first();

        if (!$otp) {
            return response()->json([
                'msg' => 'Invalid or expired recovery token.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::where('email', $otp->email)->first();

        if (!$user) {
            return response()->json([
                'msg' => 'Password changed failed Try again'
            ], Response::HTTP_NOT_FOUND);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();
        $otp->delete();

        $cookie = cookie('recovery_token', '', -1);

        return response()->json([
            'msg' => 'Password changed successfully.'
        ], Response::HTTP_ACCEPTED)->withCookie($cookie);
    }
}
