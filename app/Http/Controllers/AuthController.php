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
use Illuminate\Support\Facades\Validator;

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

    public function userProfile(){
        return response()->json([
            "message"=> "userprofiele ok",
            "userData"=> auth()->user()
        ],Response::HTTP_OK);
    }

    public function destroyUser($id){
        $usuario = User::find($id);

        if(!$usuario){
            return response()->json([
                "message"=> "Usuario no encontrado"
            ],Response::HTTP_NOT_FOUND);
            
        }

        $usuario->delete();

        return response()->json([
            "message"=> "Usuario eliminado"
        ],Response::HTTP_OK);

    }


    public function updateUser(Request $request, $id){
        $user = User::find($id);

        if(!$user){
            return response()->json([
                "message"=> "Usuario no encontrado"
            ],Response::HTTP_NOT_FOUND);
        }

        //validacion
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'nombre_usuario'=> 'required',
            'tipo_usuario' => 'required',
            'email' => 'required | email| unique:users',
            'password' => 'required|confirmed',
        ]);

        // $validator= Validator::make($request->all(),[
        //     'nombre'=> 'required|max:255',
        //     'apellido' => 'required |max:255',
        //     'nombre_usuario' => 'required|unique:user',
        //     'tipo_usuario'=>'required',
        //     'email'=> 'required|email|unique:user',
        //     'password'=>'required|confirmed'
        // ]);

      

        $user->nombre = $request->nombre;
        $user->apellido = $request->apellido;
        $user->nombre_usuario = $request->nombre_usuario;
        $user->tipo_usuario = $request->tipo_usuario;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        return response()->json([
            "message"=> "Actualizacion completa",
            'errors'=> $user
        ],Response::HTTP_ACCEPTED);

    }

    public function updatePartial(Request $request, $id){
        $usuario = User::find($id);

        if(!$usuario){
            return response()->json([
                "message"=> "Usuario no encontrado"
            ],Response::HTTP_NOT_FOUND);
        }

        #return response()->json($request,404);

        $request->validate([
            'nombre' => 'max:255',
            'apellido' => 'max:255',
            'nombre_usuario'=> 'unique:users',
            'tipo_usuario' => '',
            'email' => 'email| unique:users',
            'password' => 'confirmed',
        ]);


        if($request->has('nombre')){
            $usuario->nombre=$request->nombre;
        }
        if($request->has('apellido')){
            $usuario->apellido=$request->apellido;
        }
        if($request->has('nombre_usuario')){
            $usuario->nombre_usuario=$request->nombre_usuario;
        }
        if($request->has('tipo_usuario')){
            $usuario->tipo_usuario=$request->tipo_usuario;
        }
        if($request->has('email')){
            $usuario->email=$request->email;
        }
        if($request->has('password')){
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        return response()->json([
            "message"=> "Actualizacion completa",
            'errors'=> $usuario
        ],Response::HTTP_ACCEPTED);

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


    public function allUsers(){
        $users =  User::all();
        
        if($users->isEmpty()){
            return response()->json([
                "message"=> "Usiarios no encontrados"
            ],Response::HTTP_NOT_FOUND);
       //     $data = [
      //          'message'=> 'no se encontraron usuarios',
      //          'status'=> 200
      //      ];
      //      return response()->json($data,404);
        }

        return response()->json([
            "Usuarios"=> $users
        ],Response::HTTP_OK);

      

    }


}

