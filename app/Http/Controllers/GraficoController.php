<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class GraficoController extends Controller
{

	 public static function setGrafico($grafico, $paquetes)
    {
        $count = 0;
        $nombres = array();
        $paquetesTotal = array();
        foreach ($paquetes as $paquete) {
          $nombres[$count] = $paquete->usuarios;
          $paquetesTotal[$count] = $paquete->paquetes;
          $count++;
        }

        if ($grafico == 'Bar') {
            return GraficoController::setBarScript($nombres, $paquetesTotal);
        } else if ($grafico == 'Pie') {
            return GraficoController::setPieScript($nombres, $paquetesTotal);
        } else {
            return " ";
        }
    }

    public static function setBarScript($nombres, $paquetes)
    {
        $script = 'google.charts.load("current", {"packages":["bar"]});
                   google.charts.setOnLoadCallback(drawBarChart);
    
                   var nombres = %nombres%;
                   var paquetes =  %paquetes%;
                   nombres.unshift("Procesadores");
                   paquetes.unshift(" ");
    
                   function drawBarChart() {
                       var data = google.visualization.arrayToDataTable([
                                  nombres,
                                  paquetes
                                  ]);
    
                        var options = {
                            chart: {
                                title : "Paquetes Procesados por dia",
                            },
                            bars: "vertical"
                        };
    
                    var chart = new google.charts.Bar(document.getElementById("chart"));
                    chart.draw(data, options);
                    }';
    
        $script = str_replace("%nombres%", json_encode($nombres), $script);
        $script = str_replace("%paquetes%", json_encode($paquetes), $script);
    
        return $script;
    }

    public static function setPieScript($nombres, $paquetes) 
    {
        $script = 'google.charts.load("current", {"packages":["corechart"]});
                   google.charts.setOnLoadCallback(drawPieChart);
                   function drawPieChart() {
    
                       var data = google.visualization.arrayToDataTable([
                       %filas%
                       ]);
    
                       var options = {
                           title: "Paquetes Procesados"
                        };
    
                       var chart = new google.visualization.PieChart(document.getElementById("chart"));
                       chart.draw(data, options);
                  }';
    
          $filas = '["Usuarios", "Paquetes"],';
          $fila = '[%nombre%, %paquetes%]';
    
          for ($i=0; $i < count($nombres); $i++) { 
              $fila = str_replace("%nombre%", '"' . $nombres[$i] . '"', $fila);
              $fila = str_replace("%paquetes%", $paquetes[$i], $fila);
              $filas = $filas . $fila . ',';
              $fila = '[%nombre%, %paquetes%]';
          }
    
          $filas = substr($filas, 0, -1);
          $script = str_replace("%filas%", $filas, $script);
    
          return $script;
    }
}
