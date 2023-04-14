<?php

namespace App\Http\Controllers;

use App\Models\Historial;
use Illuminate\Http\Request;

class HistorialController extends Controller
{
    public function getHistorial(Request $req){
        $historial = Historial::where('id_user', $req->id_usuario)->get();
        echo $historial;
        return json_encode($historial);
    }
}
