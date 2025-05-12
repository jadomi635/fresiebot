<?php

namespace App\Http\Controllers;

use App\Models\Reservacion;

class ReservacionController extends Controller
{
    public function index()
    {
        $reservaciones = Reservacion::latest()->get();
        return view('reservaciones.index', compact('reservaciones'));
    }
    public function vistaAdmin()
{
    $reservaciones = Reservacion::latest()->paginate(10);
    return view('admin.reservaciones', compact('reservaciones'));
}

}

