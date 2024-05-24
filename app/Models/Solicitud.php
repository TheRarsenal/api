<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;
    protected $table = 'solicitudes'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'nombre',
        'apellido',
        'dpi',
        'telefono',
        'direccion',
        'ingresos',
        'paquete',
        'usuario_id' // ID del usuario que realiza la solicitud
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class); // Relación con el modelo Usuario
    }
}
