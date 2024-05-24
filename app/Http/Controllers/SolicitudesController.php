<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SolicitudesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $solicitudes = Solicitud::all(); // Obtiene todas las solicitudes
        if($solicitudes->isEmpty()){
            return response()->json([
                "message"=> "Solicitudes no encontradas"
            ],Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            "Solicitudes"=> $solicitudes
        ],Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = auth()->User();
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'dpi'=> 'required|integer|unique:solicitudes',
            'telefono' => 'required',
            'direccion'=>'required',
            'ingresos'=>'required',
            'paquete' => 'required|in:simple,doble,triple'
        ]);

        $solicitud = new Solicitud();
        $solicitud -> nombre = $request->nombre ;
        $solicitud -> apellido = $request->apellido ;
        $solicitud -> dpi = $request->dpi ;
        $solicitud -> telefono= $request->telefono ;
        $solicitud -> direccion= $request->direccion ;
        $solicitud -> ingresos= $request->ingresos ;
        $solicitud -> paquete= $request->paquete ;
        $solicitud -> user_id= $user->id ;
        $solicitud -> save();

        return response($solicitud, Response::HTTP_CREATED);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $solicitud = Solicitud::find($id); // Busca la solicitud con el ID especificado

        if(!$solicitud){
            return response()->json([
                "message"=> "Solicitud no encontrada"
            ],Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            "message"=> "solicitud",
            "userData"=> $solicitud
        ],Response::HTTP_OK);

        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $solicitud = Solicitud::find($id); // Busca la solicitud con el ID especificado

        if(!$solicitud){
            return response()->json([
                "message"=> "solicitud no encontrada"
            ],Response::HTTP_NOT_FOUND);
        }

        $request->validate([ // Valida los datos de entrada
            'nombre' => 'string|max:255',
            'apellido' => 'string|max:255',
            'dpi' => 'biginteger|unique:solicitudes,dpi,' . $solicitud->id, // Validación única para DPI, excluyendo el ID actual
            'telefono' => 'integer',
            'direccion' => 'string',
            'ingresos' => 'numeric',
            'paquete' => 'in:simple,doble,triple', // Validación de paquete
        ]);

        if($request->has('nombre')){
            $solicitud->nombre=$request->nombre;
        }
        if($request->has('apellido')){
            $solicitud->apellido=$request->apellido;
        }
        if($request->has('dpi')){
            $solicitud->dpi=$request->dpi;
        }
        if($request->has('telefono')){
            $solicitud->telefono=$request->telefono;
        }
        if($request->has('direccion')){
            $solicitud->direccion=$request->direccion;
        }
        if($request->has('ingresos')){
            $solicitud->ingresos = $request-> ingresos;
        }

        $solicitud->save();

        return response()->json([
            "message"=> "Actualizacion completa",
            'datos'=> $solicitud
        ],Response::HTTP_ACCEPTED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $solicitud = Solicitud::find($id); // Busca la solicitud con el ID especificado

        if(!$solicitud){
            return response()->json([
                "message"=> "solicitud no encontrada"
            ],Response::HTTP_NOT_FOUND);
        }

        $request->validate([ // Valida los datos de entrada
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dpi' => 'required|biginteger|unique:solicitudes,dpi,' . $solicitud->id, // Validación única para DPI, excluyendo el ID actual
            'telefono' => 'required|integer',
            'direccion' => 'required|string',
            'ingresos' => 'required|numeric',
            'paquete' => 'required|in:simple,doble,triple', // Validación de paquete
        ]);

        $solicitud -> nombre = $request->nombre ;
        $solicitud -> apellido = $request->apellido ;
        $solicitud -> dpi = $request->dpi ;
        $solicitud -> telefono= $request->telefono ;
        $solicitud -> direccion= $request->direccion ;
        $solicitud -> ingresos= $request->ingresos ;
        $solicitud -> paquete= $request->paquete ;

        $solicitud->save();

        return response()->json([
            "message"=> "Actualizacion completa",
            'datos'=> $solicitud
        ],Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $solicitud = Solicitud::find($id);

        if(!$solicitud){
            return response()->json([
                "message"=> "solicitud no encontrada"
            ],Response::HTTP_NOT_FOUND);
            
        }

        $solicitud->delete();

        return response()->json([
            "message"=> "solicitud eliminada"
        ],Response::HTTP_OK);
    }
}
