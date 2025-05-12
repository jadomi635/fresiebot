<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservacion extends Model
{
    protected $fillable = ['nombre', 'producto', 'fecha_reservacion', 'comentario'];

}
