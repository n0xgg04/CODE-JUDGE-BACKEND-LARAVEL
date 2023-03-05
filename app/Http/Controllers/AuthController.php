<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\Account;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'username' => 'required|max:255',
                'password' => 'required|min:1',
            ]);

            if($request->get('email')!==null){
                if(filter_var($request->get('email'),FILTER_VALIDATE_EMAIL)){
                    unset($credentials['username']);
                    $credentials['email'] = $request->get('email');
                }
            }
            // Attempt to authenticate the user
            $claims = ['exp' => time() + 3600,'username' => $credentials['username']];
            if (!$token = JWTAuth::attempt($credentials,['username' => $credentials['username']])) {
                return response()->json(['result' => 'error','message' => 'invail_credentials'], 200);
            }
           // $payload = JWTAuth::getPayload($token);
            $user = auth()->user();
           // $token = JWTAuth::fromUser($user, ['sub' => $user->access_token]);
            return response()->json(['result' => 'success','token' => $token, 'fullname' => $user->fullname,'accessToken'=>$user->access_token],200);

        } catch (ValidationException $ie) {
            return response()->json(['result' => 'error', 'message' => 'bad_request'], 400);
        }
    }

    public function register(Request $request){
        try {
            $data = $request->validate([
                'username' => 'required',
                'password' => 'required',
                'email' => 'required',
            ]);

            if(Account::where('username',$data['username'])->orWhere('email',$data['email'])->exists()){
                return response()->json(['result' => 'error','message' => 'existed_username_or_email'],200);
            }
            $user = Account::create([
                'uid' => md5(Str::random(20).Str::random(20).now()),
                'username' => $data['username'],
                'password' => Hash::make($data['password']),
                'email' => $data['email'],
                'email_verified_at' => null,
                'status' => 'active',
                'level' => 'member',
                'access_token' => md5($data['username'].Str::random(20).now()),
                'created_at' => now(),
            ]);

            $token = JWTAuth::fromUser($user);
            return response()->json(['result' => 'success','token' => $token],200);

        }catch(ValidationException $ie){
            return response()->json(['result' => 'error', 'message' => 'bad_request','debug' => $ie], 400);
        }
    }
}
