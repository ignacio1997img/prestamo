<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calendar-LoansApp</title>
    <link rel="shortcut icon" href="{{ asset('images/icon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body{
            margin: 0px auto;
            font-family: Arial, sans-serif;
            font-weight: 100;
            max-width: 740px;
        }
        #watermark {
            position: absolute;
            opacity: 0.1;
            z-index:  -1000;
        }
        #watermark-stamp {
            position: absolute;
            /* opacity: 0.9; */
            z-index:  -1000;
        }
        #watermark img{
            position: relative;
            width: 300px;
            height: 300px;
            left: 205px;
        }
        #watermark-stamp img{
            position: relative;
            width: 4cm;
            height: 4cm;
            left: 50px;
            top: 70px;
        }
        .show-print{
            display: none;
            padding-top: 15px
        }
        .btn-print{
            padding: 5px 10px
        }
        @media print{
            .hide-print, .btn-print{
                display: none
            }
            .show-print, .border-bottom{
                display: block
            }
            .border-bottom{
                border-bottom: 1px solid rgb(90, 90, 90);
                padding: 20px 0px;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/style/h.css') }}">

</head>
<body>
    <div class="hide-print" style="text-align: right; padding: 10px 0px">
        <button class="btn-print" onclick="window.close()">Cancelar <i class="fa fa-close"></i></button>
        <button class="btn-print" onclick="window.print()"> Imprimir <i class="fa fa-print"></i></button>
    </div>
    {{-- @for ($i = 0; $i < 2; $i++) --}}
 
        <table width="80%">
            <tr>
                <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="80px"></td>
                <td style="text-align: right">
                    <h2 style="margin-bottom: 0px; margin-top: 5px"></h2>
                    <small>Impreso por {{ Auth::user()->name }} - {{ date('d/m/Y H:i:s') }}</small>
                    <br>
                </td>
                <td style="text-align:center; width: 80px">
              <br>
                    {{-- <small><b>N&deg; </b></small> --}}
                </td>
            </tr>
        </table>
        <div id="watermark">
            <img src="{{ asset('images/icon.png') }}" height="100%" width="100%" /> 
        </div>
        <table width="100%" border="1" cellpadding="5" style="font-size: 12px">
            <tr>
                {{-- <td><b style="font-size: 15px">Nombre:</b> {{$loan->people->first_name}} {{$loan->people->last_name}}</td> --}}
                <td colspan="7">
                    <table>
                        <tr>
                            <td colspan="3">
                                <b style="font-size: 15px">Fecha Prestamo:</b> {{ date("d-m-Y", strtotime($loan->date)) }}
                            </td>                            
                            <td style="width: 100px">
                            </td>
                            <td colspan="3">
                                <b style="font-size: 15px">Total a Pagar:</b> Bs. {{$loan->amountTotal}} 
                            </td>
                            
                        </tr>
                        {{-- <tr>
                            <td colspan="3">
                                <b style="font-size: 15px">Total a Pagar:</b> Bs. {{$loan->amountTotal}}                                
                            </td>
                            <td style="width: 100px">

                            </td>
                            <td colspan="3">
                                <b style="font-size: 15px">Pago Diario:</b> Bs. {{$loan->amountTotal/$loan->day}}
                            </td>
                        </tr> --}}
                    </table>
                </td>

            </tr>
            
            @php
                    $meses=array(1=>"Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
                    "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                    $aux=0;

                    $fecha = \Carbon\Carbon::parse($loan->loanDay[$aux]->date);
                    $fecha = $fecha->format("N");
                    $cant = count($loan->loanDay);
                    $ok=true;

                    $inicio = \Carbon\Carbon::parse($loan->loanDay[0]->date);
                    $inicio = $inicio->format("n");

                    $fin = \Carbon\Carbon::parse($loan->loanDay[23]->date);
                    $fin = $fin->format("n");
                    // dd($fecha);
            @endphp
            @if ($inicio == $fin)   
                @php

                    $inicio1 = \Carbon\Carbon::parse($loan->loanDay[0]->date);
                    $day_f = $inicio1->format("d");                
                    $year1 = $inicio1->format("Y");                    
                    $month_f1 = $inicio1->format("m");
                    $day_f1 = date('d', mktime(0,0,0, $month_f1, 1, $year1));
                    $day_l1 = date("d", mktime(0,0,0, $month_f1+1, 0, $year1));

                    $date1 = $year1.'-'.$month_f1.'-'.$day_f1;
                    $week1 = \Carbon\Carbon::parse($date1);
                    $week1 = $week1->format("N");
                   
                    // dd($week2);
                    $aux = 0;
                    $ok = false;
                    $count = 0;
                @endphp
            
                <tr style="background-color: #666666; color: white; font-size: 18px">
                    <td colspan="7" style="text-align: center">{{$meses[$month_f1]}}</td>
                </tr>
                <tr style="background-color: #666666; color: white; font-size: 18px">
                    <td style="text-align: center">LUN</td>
                    <td style="text-align: center">MAR</td>
                    <td style="text-align: center">MIE</td>
                    <td style="text-align: center">JUE</td>
                    <td style="text-align: center">VIE</td>
                    <td style="text-align: center">SAB</td>
                    <td style="text-align: center">DOM</td>
                </tr>


                        @for ($x = 1; $x <= 6; $x++)
                            <tr>
                                @for ($i = 1; $i <= 7; $i++)      
                                        @if ($i == $week1 && !$ok)   
                                            @php
                                                $aux++;
                                                $ok=true;
                                                $count++;
                                            @endphp                                     
                                            <td @if($day_f == $aux) style="height: 40px; text-align: center; background-color: #F8FF07" @else style="height: 50px; text-align: center" @endif> <small style="font-size: 25px">{{$aux}}</small> </td>
                                        @else
                                            @if ($ok)
                                                @php
                                                    $aux++;
                                                @endphp  
                                                @if ($i != 7)
                                                    @php
                                                        $count++;
                                                    @endphp
                                                    <td @if($day_f == $aux || $count == 24) style="height: 40px; text-align: center; background-color: #F8FF07" @else style="height: 50px; text-align: center" @endif>
                                                        <small style="font-size: 25px">{{ $aux <= $day_l1? $aux:''}}</small>
                                                    </td>    
                                                @else
                                                    <td style="height: 50px; text-align: center; background-color: #CCCFD2"><small style="font-size: 25px">{{ $aux <= $day_l1? $aux:''}}</small></td>
                                                @endif                                                                                       
                                            @else
                                                <td style="height: 50px; text-align: center"></td>                                                                                           
                                            @endif
                                        @endif
                                        @if ($day_l1 == $aux)
                                            @php
                                                $x=10;
                                            @endphp
                                        @endif                             
                                @endfor  
                            </tr>          
                        @endfor  
                       
            @else
                @php

                    $inicio1 = \Carbon\Carbon::parse($loan->loanDay[0]->date);
                    $day_f = $inicio1->format("d");                
                    $year1 = $inicio1->format("Y");                    
                    $month_f1 = $inicio1->format("m");
                    $day_f1 = date('d', mktime(0,0,0, $month_f1, 1, $year1));
                    $day_l1 = date("d", mktime(0,0,0, $month_f1+1, 0, $year1));

                    $date1 = $year1.'-'.$month_f1.'-'.$day_f1;
                    $week1 = \Carbon\Carbon::parse($date1);
                    $week1 = $week1->format("N");
                    // dd($day_l1);

                    $inicio2 = \Carbon\Carbon::parse($loan->loanDay[23]->date);
                    $day_l = $inicio2->format("d");   
                    $year2 = $inicio2->format("Y");                    
                    $month_f2 = $inicio2->format("m");
                    $day_f2 = date('d', mktime(0,0,0, $month_f2, 1, $year2));
                    $day_l2 = date("d", mktime(0,0,0, $month_f2+1, 0, $year2));

                    $date2 = $year2.'-'.$month_f2.'-'.$day_f2;                    
                    $week2 = \Carbon\Carbon::parse($date2);
                    $week2 = $week2->format("N");
                    // dd($week2);
                    $aux = 0;
                    $ok = false;
                @endphp
                @for ($a = 1; $a <= 2; $a++)
                    @if ($a==1)
                        <tr style="background-color: #666666; color: white; font-size: 18px">
                            <td colspan="7" style="text-align: center">{{$meses[$month_f1]}}</td>
                        </tr>
                        <tr style="background-color: #666666; color: white; font-size: 18px">
                            <td style="text-align: center">LUN</td>
                            <td style="text-align: center">MAR</td>
                            <td style="text-align: center">MIE</td>
                            <td style="text-align: center">JUE</td>
                            <td style="text-align: center">VIE</td>
                            <td style="text-align: center">SAB</td>
                            <td style="text-align: center">DOM</td>
                        </tr>
                        @for ($x = 1; $x <= 6; $x++)
                            <tr>
                                @for ($i = 1; $i <= 7; $i++)      
                                        @if ($i == $week1 && !$ok)   
                                            @php
                                                $aux++;
                                                $ok=true;
                                            @endphp                                     
                                            <td @if($day_f == $aux) style="height: 40px; text-align: center; background-color: #F8FF07" @else style="height: 50px; text-align: center" @endif> <small style="font-size: 25px">{{$aux}}</small> </td>
                                        @else
                                            @if ($ok)
                                                @php
                                                    $aux++;
                                                @endphp  
                                                @if ($i != 7)
                                                    <td @if($day_f == $aux) style="height: 40px; text-align: center; background-color: #F8FF07" @else style="height: 50px; text-align: center" @endif>
                                                        <small style="font-size: 25px">{{ $aux <= $day_l1? $aux:''}}</small>
                                                    </td>    
                                                @else
                                                    <td style="height: 50px; text-align: center; background-color: #CCCFD2"><small style="font-size: 25px">{{ $aux <= $day_l1? $aux:''}}</small></td>
                                                @endif                                                                                       
                                            @else
                                                <td style="height: 50px; text-align: center"></td>                                                                                           
                                            @endif
                                        @endif
                                        @if ($day_l1 == $aux)
                                            @php
                                                $x=10;
                                            @endphp
                                        @endif                             
                                @endfor  
                            </tr>          
                        @endfor  
                    @else
                        <tr style="background-color: #666666; color: white; font-size: 18px">
                            <td colspan="7" style="text-align: center">{{$meses[$month_f2]}}</td>
                        </tr>
                        <tr style="background-color: #9b9a9a; color: white; font-size: 18px">
                            <td style="text-align: center">LUN</td>
                            <td style="text-align: center">MAR</td>
                            <td style="text-align: center">MIE</td>
                            <td style="text-align: center">JUE</td>
                            <td style="text-align: center">VIE</td>
                            <td style="text-align: center">SAB</td>
                            <td style="text-align: center">DOM</td>
                        </tr>
                        @php
                            $aux = 0;
                            $ok = false;
                        @endphp
                        @for ($x = 1; $x <= 6; $x++)
                            <tr>
                                @for ($i = 1; $i <= 7; $i++)      
                                        @if ($i == $week2 && !$ok)   
                                            @php
                                                $aux++;
                                                $ok=true;
                                            @endphp                                     
                                            <td @if($day_l == $aux) style="height: 40px; text-align: center; background-color: #F8FF07" @else style="height: 50px; text-align: center" @endif> <small style="font-size: 25px">{{$aux}}</small> </td>
                                        @else
                                            @if ($ok)
                                                @php
                                                    $aux++;
                                                @endphp  
                                                @if ($i != 7)
                                                    <td @if($day_l == $aux) style="height: 40px; text-align: center; background-color: #F8FF07" @else style="height: 50px; text-align: center" @endif> <small style="font-size: 25px">{{ $aux <= $day_l2? $aux:''}}</small></td>    
                                                @else
                                                    <td style="height: 50px; text-align: center; background-color: #CCCFD2"><small style="font-size: 25px">{{ $aux <= $day_l2? $aux:''}}</small></td>
                                                @endif                                                                                       
                                            @else
                                                <td style="height: 50px; text-align: center"> <small style="font-size: 25px"></td>                                                                                           
                                            @endif
                                        @endif
                                        @if ($day_l2 == $aux)
                                            @php
                                                $x=10;
                                            @endphp
                                        @endif                             
                                @endfor  
                            </tr>          
                        @endfor   
                    @endif        
                @endfor            
            @endif
            {{-- <tr>
                <td rowspan="2">
                    <b>NOMBRE: </b><br>
                    <b>CI: </b><br>
                    <b>CARGO: </b><br>
                    <b>AFP: </b><br>
                    <b>NUA/CUA: </b><br>
                    <b>MODALIDAD DE CONTRATACIÓN: </b> 
                </td>
                <td valign="top">
                    <b>PERIODO: </b> <br>
                    <b>DÍAS TRABAJADOS: </b><br>
                </td>
            </tr>
            <tr>
                <td>
                    <b>NIVEL SALARIAL: </b> <br>
                    <b>SUELDO MENSUAL: </b><br>
                    <b>SUELDO PARCIAL: </b><br>
                    <b>BONO ANTIGÜEDAD: </b><br>
                    <b>TOTAL GANADO: </b><br>
                </td>
            </tr>
            <tr>
                <td style="text-align: center"><b>DESCUENTOS</b></td>
                <td rowspan="3" valign="bottom" style="text-align: center"><b><small>SELLO Y FIRMA</small></b></td>
            </tr>
            <tr>
                <td>
                    <br>
                    <b>APORTE LABORAL AFP:</b><br>
                    <b>RC IVA:</b><br>
                    <b>MULTAS:</b><br>
                    <b>TOTAL DESCUENTOS:</b>  <br>
                    <br>
                </td>
            </tr>
            <tr>
                <td>
                    <br>
                    <b>LÍQUIDO PAGABLE: </b>
                    <br> <br>
                </td>
            </tr> --}}
        </table>

    {{-- @endfor --}}

    <script>
        document.body.addEventListener('keypress', function(e) {
            switch (e.key) {
                case 'Enter':
                    window.print();
                    break;
                case 'Escape':
                    window.close();
                default:
                    break;
            }
        });
    </script>
</body>
</html>