@extends('layout')

@section('tittle')
	Paquetes Procesados
@stop

@section('header')
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
	<script>
     var time = new Date().getTime();
     $(document.body).bind("mousemove keypress", function(e) {
         time = new Date().getTime();
     });

     function refresh() {
         if(new Date().getTime() - time >= 60000) 
             window.location.reload(true);
         else 
             setTimeout(refresh, 10000);
     }

     setTimeout(refresh, 10000);
	</script>
@stop

@section('script')
	<script type="text/javascript">
	<?php echo $script; ?>
	</script>
@stop

@section('content')
	<h1>Paquetes Procesados
		@if ($flagFechaFin)
			{{ "desde " . $fechaIni . " hasta " . $fechaFin }}
		@else
			{{ "el " . $fechaIni }}
		@endif

	</h1>
	
	<div class="table-responsive">
	<table id="paquetes" class="table table-striped table-hover table-bordered">
		<tr style="background-color:#337ab7; color:white;">
			<th>USUARIOS</th>
			<th>PAQUETES</th>
			<th>ULTIMO PAQUETE PROCESADO</th>
		</tr>
		<tr>
		@foreach ($paquetes as $paquete)
			<td>{{ $paquete->usuarios }}</td>
			<td>{{ $paquete->paquetes }}</td>
			<td>{{ $paquete->ultimo }}</td>
		</tr>
		@endforeach
		<tr>
			<td>TOTAL</td>
			<td>{{ $total }}</td>
		</tr>
		</table>
	</div>

	<form method="GET" id="consulta">
		<div class="form-group">
			<label>Fecha Inicial
				<input name="fecha" type="date" class="form-control">
			</label>
			<label>Fecha Final
				<input name="fechaFin" type="date" class="form-control">	
			</label>
		</div>
		<div class="form-group">
  			<label>Seleccionar Grafico:</label>
  			<select class="form-control" name="grafico" id="grafico">
    			<option value="Bar">Barras</option>
    			<option value="Pie">Circular</option>
  			</select>
		</div>
		<div class="form-gruop">
			<button type="submit" class="btn btn-primary">Consultar</button>
		</div>
	</form>
	<br>
	<div id="chart" style="width: 900px; height: 500px;"></div>
	<br>
	@if (count($errors))
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
     @endif

@stop
	