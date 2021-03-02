<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Traits\ApiResponder;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    use ApiResponder;

    public function login(Request $req)
    {
        $user = User::where('email',$req->email)->firstOrFail();
        if($user){
            if(Hash::check($req->password, $user->password))
            {
                $token = $user->createToken(Str::random(80))->plainTextToken;
                $user->api_token = $token;
                $user->save();
                return response()->json(['data' => $user],200);
            }
        }
        else
        {
            return $response()->json(['error' => 'Couldnt log you in'],404);
        }
    }

    public function register(Request $req)
    {
        $validator = Validator::make($req->all(),[
            'name' => 'required|min:3|max:25',
            'email' =>'required',
            'password' => 'required|min:8|max:12'
        ]);
        if($validator->fails()){
            return response()->json(['error' => 'Validation failed!'],404);
        }
        else
        {
            $user = User::create(['name' => $req->name,'email' => $req->email,'password' => Hash::make($req->password)]);
            if($user){
                return response()->json(['data' => $user],200);
            }
        }
    }

    public function logout(Request $request){
        // Get user who requested the logout
        $user = request()->user(); //or Auth::user()
        // Revoke current user token
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
    }
}
