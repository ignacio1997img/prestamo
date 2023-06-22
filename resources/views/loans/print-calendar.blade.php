@extends('layouts.template-print-alt')

@section('page_title', 'Reporte')

@section('content')

    <h3>NOMBRE: {{$loan->people->first_name}} {{$loan->people->last_name1}} {{$loan->people->last_name2}}</h3>
    <h3>MONTO: {{$loan->amountTotal/$loan->day}}</h3>
    <table width="100%" border="1" cellpadding="5" style="font-size: 12px">
                                    
        @php
                $meses=array(1=>"Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
                    "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                
                $fechaInicio = \Carbon\Carbon::parse($loan->loanDay[0]->date);
                $mesInicio = $fechaInicio->format("n"); //para saber desde que mes empiesa la cuota                        
                $diaInicio = $fechaInicio->format("d"); //para saber en que dia se paga la cuota
                $anoInicio = $fechaInicio->format("Y"); //para saber en que año empiesa la cuota
                // dd($diaInicio);
                $inicio = $anoInicio.'-'.($mesInicio<=9?'0'.$mesInicio : ''.$mesInicio).'-'.$diaInicio;
                // dd($inicio);


                
                $fechaFin = \Carbon\Carbon::parse($loan->loanDay[count($loanday)-1]->date);
                $mesFin = $fechaFin->format("n"); //para saber hasta que mes termina la cuota                        
                $diaFin = $fechaFin->format("d"); //para saber hasta que dia termina la cuota
                $anoFin = $fechaFin->format("Y"); //para saber hasta que año termina la cuota
                // dd($fechaFin);
                $fin = $anoFin.'-'.($mesFin<=9?'0'.$mesFin : ''.$mesFin).'-'.$diaFin;

                // $aux <= 9 ? '-0'.$aux : '-'.$aux
                // dd($fin);

                $cantMeses = count($cantMes); //para la cantidad de meses que hay entre las dos fecha
                $mes = 0;

                $number=0;
                $cantNumber = count($loanday);

                $okNumber =0;
                // dd($cantNumber);

                
        @endphp
        
        @while ($mes < $cantMeses)
            <tr style="background-color: #666666; color: white; font-size: 18px">
                <td colspan="7" style="text-align: center">{{$loan->code}} - {{$meses[intval($cantMes[$mes]->mes)]}}/{{intval($cantMes[$mes]->ano)}}</td>
            </tr>
            <tr style="background-color: #666666; color: white; font-size: 18px">
                <td style="text-align: center; width: 15%">LUN</td>
                <td style="text-align: center; width: 15%">MAR</td>
                <td style="text-align: center; width: 15%">MIE</td>
                <td style="text-align: center; width: 15%">JUE</td>
                <td style="text-align: center; width: 15%">VIE</td>
                <td style="text-align: center; width: 15%">SAB</td>
                <td style="text-align: center; width: 10%">DOM</td>
            </tr>

            @php
                $primerDia = date('d', mktime(0,0,0, intval($cantMes[$mes]->mes), 1, intval($cantMes[$mes]->ano)));//para obtener el primer dia del mes
                $primerFecha = intval($cantMes[$mes]->ano).'-'.intval($cantMes[$mes]->mes).'-'.$primerDia; // "20XX-XX-01"concatenamos el primer dia ma sel mes y el año del la primera cuota
                $posicionPrimerFecha = \Carbon\Carbon::parse($primerFecha);
                $posicionPrimerFecha = $posicionPrimerFecha->format("N"); //obtenemos la posicion de la fecha en que dia cahe pero en numero

               
                $ultimoDia = date("d", mktime(0,0,0, intval($cantMes[$mes]->mes)+1, 0, intval($cantMes[$mes]->ano)));//para obtener el ultimo dia del mes
                $ok = false;

                $dia=0;
            @endphp
            
            @for ($x = 1; $x <= 6; $x++)
                <tr>
                    @for ($i = 1; $i <= 7; $i++) 

                        @if ($i == $posicionPrimerFecha && !$ok)
                            @php
                                $dia++;
                                $ok=true;
                                $fecha = $cantMes[$mes]->ano.'-'.$cantMes[$mes]->mes.($dia<=9?'-0'.$dia:'-'.$dia);
                                // dd($fecha);
                            @endphp
                            <td 
                                @if($i == 7)
                                    style="height: 60px; text-align: center; background-color: #CCCFD2"
                                @endif
                                @if(($fecha == $inicio || $fecha == $fin) && $i != 7)
                                    @php
                                        $okNumber++;
                                    @endphp
                                    style="height: 60px; text-align: center; background-color: #F8FF07;"
                                @else
                                    style="height: 60px; text-align: center"
                                @endif>
                                {{-- ____________________________________________ --}}
                                <small style="font-size: 25px;">{{$dia}}</small>  

                            </td>
                        @else
                            @if ($ok && $dia < $ultimoDia){{-- para que muestre hasta el ultimo dia del mes  --}}
                                @php
                                    $dia++;
                                    $fecha = $cantMes[$mes]->ano.'-'.$cantMes[$mes]->mes.($dia<=9?'-0'.$dia:'-'.$dia);
                                @endphp       
                                <td
                                    @if($i == 7)
                                        style="height: 60px; text-align: center; background-color: #CCCFD2"
                                    @endif
                                    @if(($fecha == $inicio || $fecha == $fin) && $i != 7)
                                        @php
                                            $okNumber++;
                                        @endphp
                                        style="height: 60px; text-align: center; background-color: #F8FF07;"
                                    @else
                                        style="height: 60px; text-align: center;"
                                    @endif>
                                    {{-- ____________________________________________ --}}
                                    <small style="font-size: 25px;">{{$dia}}</small>                                   
                                    
                                    
                                    
                                   
                                </td>                                                                                                                                             
                            @else
                                <td style="height: 60px; text-align: center"></td>                                                                                           
                            @endif
                        @endif
                        @if ($dia == $ultimoDia)
                            @php
                                $x=10;
                            @endphp
                        @endif 
                    @endfor  
                </tr>          
            @endfor  
            @php
                $mes++;
            @endphp
        @endwhile   
        @php
            // dd($number);
        @endphp                                
    </table>

@endsection