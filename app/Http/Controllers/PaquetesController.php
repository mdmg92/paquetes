<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;

class PaquetesController extends Controller
{
    public function show(Request $request)
    {
    	//$paquetes = DB::table('Recepcionpaq')
    	//				->select(DB:raw('upper(Usuario) as Usuarios'), 
    	//					DB::raw('count(codigopaq) as paquetes'), 
    	//					DB::raw('max(time_date) as ultimo'))
    	//				->whereBetween('time_date', [date('Y-m-d'), 
      //                    date('Y-m-d', strtotime(date('Y-m-d'))+86400)])
    	//				->where('sucursalRec', 'MIA')
    	//				->groupBy('usuarios')
    	//				->orderBy('paquetes');
        
        $this->validate($request, [
            'fecha' => 'date|before:tomorrow|required with:fechaFin',
            'fechaFin' => 'date|after:fecha',
            'grafico' => 'in:Bar,Pie,None'
        ]);

        $flagFechaFin = False;
        $grafico = $request->input('grafico');

        $fechaIni = date('Y-m-d', strtotime($request->input('fecha')));
        if (empty($request->input('fechaFin'))) {
            $fechaFin = date('Y-m-d', strtotime($request->input('fecha')) + 86400);
        } else {
            $flagFechaFin = True;
            $fechaFin = date('Y-m-d', strtotime($request->input('fechaFin')));
        }

        $paquetes = DB::select('select upper(usuario) as usuarios, 
                                count(codigopaq) as paquetes, 
                                max(time_date) as ultimo
                                from Recepcionpaq
                                where time_date 
                                between cast("'. $fechaIni .'" as date) 
                                    and cast("'. $fechaFin .'" as date)
                                and SucursalRec = "MIA"
                                group by usuarios
                                order by paquetes desc');
    	  
        $total = 0;
        foreach ($paquetes as $paquete) {
          $total += $paquete->paquetes;
        }
        
        $script = GraficoController::setGrafico($grafico, $paquetes);

        return view('paquetes.paquetes', compact('paquetes', 
                                                'fechaIni', 
                                                'fechaFin', 
                                                'flagFechaFin',
                                                'script',
                                                'total'));
    }
}
