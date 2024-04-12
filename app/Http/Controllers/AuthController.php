<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{

    public function conf(Request $request){
        return response(["message"=>"bienvenido"],Response::HTTP_ACCEPTED);
    }

    public function register(Request $request){
        


        //validacion
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'nombre_usuario'=> 'required',
            'tipo_usuario' => 'required',
            'email' => 'required | email| unique:users',
            'password' => 'required|confirmed',
        ]);


        //alta de usuario
        $user = new User();
        $user->nombre = $request->nombre;
        $user->apellido = $request->apellido;
        $user->nombre_usuario = $request->nombre_usuario;
        $user->tipo_usuario = $request->tipo_usuario;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        //respuesta
        // return response()->json([
        //     'message'=> 'metodo ok'
        // ]);

        return response($user, Response::HTTP_CREATED);


    }

    public function login(Request $request){
       $credenciales = $request->validate([
            'nombre_usuario' => ['required'],
            'password' => ['required']
       ]);

       if(Auth::attempt($credenciales)){
            $user = Auth::user();
            $token = $user ->createToken('token')->plainTextToken;
            $cookie = cookie('cookie_token', $token, 60*24);
            return response(["token"=>$token, "tipo_usuario" => auth()->user()->tipo_usuario], Response::HTTP_OK)->withoutCookie($cookie);
       }else{
            return response(["message"=>"credenciales invalidas"],Response::HTTP_UNAUTHORIZED);
       }
    }

    public function userProfile(Request $request){
        return response()->json([
            "message"=> "userprofiele ok",
            "userData"=> auth()->user()
        ],Response::HTTP_OK);
    }

    // public function logout(){
    //     $cookie = Cookie::forget('cookie_token');
    //     return response(["message"=>"cierre de secion exitoso"],Response::HTTP_OK)->withCookie($cookie);
    // }

    public function logout(Request $request)
{
    $user = Auth::user();
    $user->tokens->each(function ($token) {
        $token->delete();
    });

    $cookie = Cookie::forget('cookie_token');
    return response(["message" => "Cierre de sesión exitoso"], Response::HTTP_OK)->withCookie($cookie);
}


    public function allUsers(Request $request){
    }


}

