<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
    public function login(Request $request){

        $validator= Validator::make($request->all(),[
            "email"=>"required|email", 
            "password"=>"required", 
         ]);
         if($validator->fails()){
            return send_error('validation error',$validator->errors(),422);
        }
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            // return response([
            //     'message' => 'You have entered incorrect email or mobile or password'
            // ], 401);
            return send_error('Unauthorized ','You have entered incorrect email or password',401);

        } else {

            $token = $user->createToken('PassportAuthToken')->accessToken;

            $response = [
                'message'=> "User are successfully Login !",
                'user' =>   $user,
                'token' => $token,
            ];
          return response($response, 200);
        }
        
    }

    public function register(Request $request){

       $validator= Validator::make($request->all(),[
           "name"=>"required|min:4", 
           "email"=>"required|email|unique:users", 
           "password"=>"required|min:6", 
        ]);
        if($validator->fails()){
            return send_error('validation error',$validator->errors(),422);
        //  return response()->json([
        //     'message'=>"Validator error",
        //     'data'=> $validator->errors()
        //  ],422);
        }
        try{
            $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            ]);

            $data=[
                'name' => $user->name,
            ];
                // send_success helper function
            return send_success("User registation Success !", $data);

            // return response()->json([  
            
            //     'status'=>true,
            //     'message'=> "User registation Success !",
            //     'name' => $user->name,
            //  ]);
            
        }catch(Exception $e){
            // send_error helper function
            return send_error($e->getMessage(),$e->getCode(),422);

        }
     
    }
}
