@extends('voyager::master')

@section('page_title', 'Abonar Pago')

{{-- @if (auth()->user()->hasPermission('add_contracts') || auth()->user()->hasPermission('edit_contracts')) --}}

    @section('page_header')
        <h1 id="titleHead" class="page-title">
            <i class="voyager-dollar"></i> Abonar Pago
        </h1>
        <a href="{{ route('loans.index') }}" class="btn btn-warning">
            <i class="fa-solid fa-rotate-left"></i> <span>Volver</span>
        </a>
    @stop

    @section('content')
        <div class="page-content edit-add container-fluid">    
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        {{-- <div class="panel-heading">
                            <h6 id="h4" class="panel-title">Detalle del Prestamos</h6>
                        </div> --}}
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="panel-heading" style="border-bottom:0;">
                                        <h3 id="h4" class="panel-title">Fecha de solicitud</h3>
                                    </div>
                                    <div class="panel-body" style="padding-top:0;">
                                        <small>{{date("d-m-Y", strtotime($loan->date))}}</small>
                                    </div>
                                    <hr style="margin:0;">
                                </div>
                                <div class="col-md-4">
                                    <div class="panel-heading" style="border-bottom:0;">
                                        <h3 id="h4" class="panel-title">Ruta Asiganada</h3>
                                    </div>
                                    <div class="panel-body" style="padding-top:0;">
                                        <small>{{$route->route->name}}</small>
                                    </div>
                                    <hr style="margin:0;">
                                </div>   
                                <div class="col-md-4">
                                    <div class="panel-heading" style="border-bottom:0;">
                                        <h3 id="h4" class="panel-title">Garante</h3>
                                    </div>
                                    <div class="panel-body" style="padding-top:0;">
                                        <small>{{$loan->guarantor_id? $loan->guarantor->first_name.' '.$loan->guarantor->last_name1.' '.$loan->guarantor->last_name2:'SN'}}</small>
                                    </div>
                                    <hr style="margin:0;">
                                </div>                               
                            </div>
                            <div class="row">
                                    <div class="col-md-4">
                                        <div class="panel-heading" style="border-bottom:0;">
                                            <h3 id="h4" class="panel-title">CI</h3>
                                        </div>
                                        <div class="panel-body" style="padding-top:0;">
                                            <small>{{$loan->people->ci}}</small>
                                        </div>
                                        <hr style="margin:0;">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="panel-heading" style="border-bottom:0;">
                                            <h3 id="h4" class="panel-title">Beneficiario</h3>
                                        </div>
                                        <div class="panel-body" style="padding-top:0;">
                                            <small>{{$loan->people->first_name}} {{$loan->people->last_name}}</small>
                                        </div>
                                        <hr style="margin:0;">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="panel-heading" style="border-bottom:0;">
                                            <h3 id="h4" class="panel-title">Celular</h3>
                                        </div>
                                        <div class="panel-body" style="padding-top:0;">
                                            <small>{{$loan->people->cell_phone?$loan->people->cell_phone:'SN'}}</small>
                                        </div>
                                        <hr style="margin:0;">
                                    </div>                                 
                            </div>

                            <h3 id="h3" style="text-align: center"><i class="fa-solid fa-calendar-days"></i> {{$loan->code}}</h3>
                            <hr>
                            {{-- @if ($cashier_id==0 && $loan->debt != 0 )  
                                    <div class="alert alert-warning">
                                        <strong>Advertencia:</strong>
                                        <p>No puedes abonar dinero al prestamo.</p>
                                    </div>
                            @endif --}}
                            @if (!$cashier)
                                <div class="alert alert-warning">
                                    <strong>Advertencia:</strong>
                                    <p>No puedes abonar dinero al prestamo.</p>
                                </div>
                            @endif

                            <div class="col-md-12">
                                <button type="button" onclick="javascript:imprim1(imp1);">Imprimir</button>
                            </div>
                            
                            <div class="col-md-8" id="imp1">
                                
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
                                            <td colspan="7" style="text-align: center">{{$meses[intval($cantMes[$mes]->mes)]}} - {{intval($cantMes[$mes]->ano)}}</td>
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
                                                                style="height: 80px; text-align: center; background-color: #CCCFD2"
                                                            @endif
                                                            @if(($fecha == $inicio || $fecha == $fin) && $i != 7)
                                                                @php
                                                                    $okNumber++;
                                                                @endphp
                                                                style="height: 80px; text-align: center; background-color: #F8FF07;"
                                                            @else
                                                                style="height: 80px; text-align: center"
                                                            @endif>
                                                            {{-- ____________________________________________ --}}
                                                            <small style="font-size: 25px;">{{$dia}}</small>      
                                                            <br>
                                                        

                                                            @if (($okNumber==1 || $okNumber==2 )&& $i != 7)
                                                                @php
                                                                    if($okNumber == 2)
                                                                    {
                                                                        $okNumber++;
                                                                    }
                                                                    $number++;
                                                                @endphp 

                                                                @if ($loan->loanDay[$number-1]->late==1)
                                                                    <img src="{{ asset('images/icon/atrazado.png') }}" width="20px">
                                                                @endif  
                                                                @if ($loan->loanDay[$number-1]->debt==0)
                                                                    <img src="{{ asset('images/icon/pagado.png') }}" width="80px">
                                                                @endif           
                                                                    
                                                                @if ($loan->loanDay[$number-1]->debt != $loan->loanDay[$number-1]->amount && $loan->loanDay[$number-1]->debt > 0)                                                                      
                                                                        <strong style="font-size: 20px; color:#440505">Bs. {{$loan->loanDay[$number-1]->amount - $loan->loanDay[$number-1]->debt}}</strong>                                                                       
                                                                 @endif                                                                                                                     
                                                            @endif

                                                            

                                                            {{-- <img src="{{ asset('images/icon/pagado.png') }}" width="80px"> --}}

                                                        </td>
                                                    @else
                                                        @if ($ok && $dia < $ultimoDia){{-- para que muestre hasta el ultimo dia del mes  --}}
                                                            @php
                                                                $dia++;
                                                                $fecha = $cantMes[$mes]->ano.'-'.$cantMes[$mes]->mes.($dia<=9?'-0'.$dia:'-'.$dia);
                                                            @endphp       
                                                            <td
                                                                @if($i == 7)
                                                                    style="height: 80px; text-align: center; background-color: #CCCFD2"
                                                                @endif
                                                                @if(($fecha == $inicio || $fecha == $fin) && $i != 7)
                                                                    @php
                                                                        $okNumber++;
                                                                    @endphp
                                                                    style="height: 80px; text-align: center; background-color: #F8FF07;"
                                                                @else
                                                                    style="height: 80px; text-align: center;"
                                                                @endif>
                                                                {{-- ____________________________________________ --}}
                                                                <small style="font-size: 25px;">{{$dia}}</small>
                                                                <br>

                                                                @if (($okNumber==1 || $okNumber==2 )&& $i != 7)
                                                                    @php
                                                                        if($okNumber == 2)
                                                                        {
                                                                            $okNumber++;
                                                                        }
                                                                        $number++;
                                                                    @endphp 

                                                                    @if ($loan->loanDay[$number-1]->late==1)                                                                        
                                                                        <img src="{{ asset('images/icon/atrazado.png') }}" width="20px">                                                                          
                                                                    @endif
                                                                    @if ($loan->loanDay[$number-1]->debt != $loan->loanDay[$number-1]->amount && $loan->loanDay[$number-1]->debt > 0)                                                                      
                                                                        <strong style="font-size: 20px; color:#440505">Bs. {{$loan->loanDay[$number-1]->amount - $loan->loanDay[$number-1]->debt}}</strong>                                                                       
                                                                    @endif
                                                                    @if ($loan->loanDay[$number-1]->debt==0 )                                        
                                                                        <img src="{{ asset('images/icon/pagado.png') }}" width="70px">                                                                            
                                                                    @endif 
                                                                                                                                                                                                     
                                                                @endif
                                                                  
                                                                
                                                                
                                                                
                                                               
                                                            </td>                                                                                                                                             
                                                        @else
                                                            <td style="height: 80px; text-align: center"></td>                                                                                           
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
                            </div>

                            <div class="col-md-4">
                                @if (auth()->user()->hasPermission('addMoneyDaily_loans') && $cashier)
                                
                            
                                    @if ($loan->debt != 0 && $cashier->status=='abierta')                                
                                    
                                        <form id="form-abonar-pago" action="{{route('loans-daily-money.store')}}" method="POST">
                                        @csrf
                                            <div class="row">
                                                <input type="hidden" name="date" value="{{$date}}">
                                                <input type="hidden" name="cashier_id" value="{{$cashier->id}}">
                                                
                                                
                                                    <input type="hidden" name="loan_id" value="{{$loan->id}}">
                                              
                                                <div class="form-group col-md-6">
                                                    <small>Cuota</small>
                                                    <input type="number" name="amount" id="amount" min="0.1" step=".01" onkeypress="return filterFloat(event,this);" onchange="subTotal()" onkeyup="subTotal()" style="text-align: right" class="form-control text" required>                                    
                                                    <b class="text-danger" id="label-amount" style="display:none">El monto incorrecto..</b>
                                                </div>   
                                                <div class="form-group col-md-6">
                                                    <small>Registrado Por</small>
                                                    <select name="agent_id" id="agent_id" class="form-control select2" required>
                                                        <option value="{{$register->id}}" selected>{{$register->name}} - {{$register->role->name}}</option>                                  
                                                    </select>
                                                </div>                                
                                            </div>
                                            <label class="checkbox-inline"><input type="checkbox" value="1" required>Confirmar Pago..!</label>

                                            <div class="row">
                                                <div class="col-md-12 text-right">
                                                    <button type="submit" id="btn-sumit" style="display:block" disabled class="btn btn-success btn-sumit"><i class="fa-solid fa-money-bill"></i> Pagar</button>
                                                </div>
                                            </div>
                                        </form> 
                                    @endif
                                @endif

                                <div class="row">
                                    <table width="100%" cellpadding="20" class="table">
                                        <tr>
                                            <td ><small>Pago Diario</small></td>
                                            <td class="text-right"><h3 id="h4">Bs.{{ number_format($loan->amountTotal/$loan->day, 2, ',', '.') }}</h3></td>
                                        </tr>
                                    </table>
                                    <h3 id="h4" style="text-align: center">Atrazo</h3>

                                    <table width="100%" cellpadding="20" class="table">
                                        <tr>
                                            <td class="text-right"><h3 id="h4">Dias. {{ $loanday->where('debt', '>', 0)->where('late', 1)->count() }}</h3></td>
                                            <td class="text-right"><h3 id="h4">Bs. {{ number_format($loanday->where('debt', '>', 0)->where('late', 1)->sum('debt'), 2, ',', '.') }}</h3></td>
                                        </tr>
                                    </table>
                                </div>


                                <div class="row">
                                    {{-- <div class="panel-heading">
                                        <h6 id="h4" class="panel-title">Detalle de deuda</h6>
                                    </div> --}}
                                    <canvas id="myChart"></canvas>
                                </div>
                                {{-- <hr> --}}
                                <div class="row">
                                    <table width="100%" cellpadding="20">
                                        <tr>
                                            <td><small>Monto Pagado</small></td>
                                            <td class="text-right"><h3>{{ number_format($loan->amountTotal - $loan->debt, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                        </tr>
                                        <tr>
                                            <td><small>Deuda</small></td>
                                            <td class="text-right"><h3>{{ number_format($loan->debt, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                        </tr>
                                        
                                        {{-- <tr>                                            
                                            <td><small>TOTAL PAGADO</small></td>
                                            <td class="text-right"><h3>{{ number_format($loan->amountTotal, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                        </tr> --}}
                                    </table>
                                </div>
                                <h3 id="h4" style="text-align: center"><i class="voyager-dollar"></i> Detalle del Pago</h3>
                                <hr>
                                <div class="row">
                                    <table width="100%" cellpadding="20">
                                        <tr>
                                            <td><small>Dias Total a Pagar</small></td>
                                            <td class="text-right"><h3>{{ number_format($loan->day, 2, ',', '.') }} </h3></td>
                                        </tr>
                                        <tr>                                            
                                            <td><small>Pago Diario</small></td>
                                            <td class="text-right"><h3>{{ number_format($loan->amountTotal/$loan->day, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                        </tr>
                                    </table>
                                </div>
                                <h3 id="h4" style="text-align: center"><i class="voyager-dollar"></i> Detalle del Prestamo</h3>
                                <hr>
                                <div class="row">
                                    <table width="100%" cellpadding="20">
                                        <tr>
                                            <td><small>Monto Prestado</small></td>
                                            <td class="text-right"><h3>{{ number_format($loan->amountLoan, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                        </tr>
                                        <tr>
                                            <td><small>Interes a Pagar</small></td>
                                            <td class="text-right"><h3>{{ number_format($loan->amountPorcentage, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                        </tr>
                                        <tr>                                            
                                            <td><small>TOTAL A PAGAR</small></td>
                                            <td class="text-right"><h3>{{ number_format($loan->amountTotal, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="row">
                                    <small>Observación</small>
                                    <textarea name="observation" id="observation" disabled class="form-control text" cols="30" rows="3">{{$loan->observation}}</textarea>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>                
            </div>            
        </div>

        <div id="popup-button">
            <div class="col-md-12" style="padding-top: 5px">
                <h4 class="text-muted">Desea imprimir el comprobante?</h4>
            </div>
            <div class="col-md-12 text-right">
                <button onclick="javascript:$('#popup-button').fadeOut('fast')" class="btn btn-default">Cerrar</button>
                <a id="btn-print" onclick="printDailyMoney()"  title="Imprimir" class="btn btn-danger">Imprimir <i class="glyphicon glyphicon-print"></i></a>
                {{-- <button type="submit" id="btn-print" title="Imprimir" class="btn btn-danger" onclick="printDailyMoney()" class="btn btn-primary">Imprimir <i class="glyphicon glyphicon-print"></i></button> --}}

            </div>
        </div>
    @stop

    @section('css')
        <style>
.form-group{
            margin-bottom: 10px !important;
        }
        .label-description{
            cursor: pointer;
        }
        #popup-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 400px;
            height: 100px;
            background-color: white;
            box-shadow: 5px 5px 15px grey;
            z-index: 1000;

            /* Mostrar/ocultar popup */
            /* @if(session('loan_id')) */
                animation: show-animation 1s;
            /* @else */
            right: -500px;
            /* @endif */
        }

        @keyframes show-animation {
            0% {
                right: -500px;
            }
            100% {
                right: 20px;
            }
        }
        </style>
    @endsection

    @section('javascript')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.min.js"></script>
        <script>
            function inputNumeric(event) {
                // var amount = document.getElementById(amount).value;
                // alert(event.charCode);
                // if(event.charCode >= 48 && event.charCode <= 57){
                //     return true;
                // }
                // return false;        
            }
        </script>
        <script>
            $(document).ready(function(){
                $("#amount").on('paste', function(e){
                    e.preventDefault();
                    // alert('Esta acción está prohibida');
                })  

                $('#form-abonar-pago').submit(function(e){
                    $('.btn-sumit').attr('disabled', true);
                    // $('#btn-sumit').css('display', 'none');

                });
            })
            $(document).ready(function(){                

                const data = {
                    labels: [
                        'Deuda Total',
                        'Total Pagado'
                    ],
                    datasets: [{
                        label: 'My First Dataset',
                        data: ["{{$loan->debt}}", "{{$loan->amountTotal - $loan->debt}}"],
                        backgroundColor: [
                        'red',
                        'rgb(54, 205, 1)'
                        ],
                        hoverOffset: 4
                    }]
                };
                const config = {
                    type: 'pie',
                    data: data,
                };
                var myChart = new Chart(
                    document.getElementById('myChart'),
                    config
                );               
            });
            function subTotal()
            {
                let amount = $(`#amount`).val() ? parseFloat($(`#amount`).val()) : 0;


                let debt = {{$loan->debt}}
                // if(amount > debt)
                // {
                //     $('#btn-sumit').attr('disabled', 'disabled');
                //     $('#label-amount').css('display', 'block');
                // }
                // else
                // {
                //     $('#btn-sumit').removeAttr('disabled');
                //     $('#label-amount').css('display', 'none');

                // }

                if(amount <= 0 || amount > debt)
                {
                    $('#btn-sumit').attr('disabled', 'disabled');
                    $('#label-amount').css('display', 'block');
                }
                else
                {
                    $('#btn-sumit').removeAttr('disabled');
                    $('#label-amount').css('display', 'none');
                }


                
            
            }

            let loan_id=0;
            let transaction_id=0;
            $(document).ready(function(){

                @if(session('loan_id'))
                    loan_id = "{{ session('loan_id') }}";
                    transaction_id = "{{ session('transaction_id') }}";
                @endif

                // Ocultar popup de impresión
                setTimeout(() => {
                    $('#popup-button').fadeOut('fast');
                }, 8000);
            });
            function printDailyMoney()
            {
                // alert(transaction_id);
                window.open("{{ url('admin/loans/daily/money/print') }}/"+loan_id+"/"+transaction_id, "Recibo", `width=700, height=700`)
            }
            















            function filterFloat(evt,input){
                // Backspace = 8, Enter = 13, ‘0′ = 48, ‘9′ = 57, ‘.’ = 46, ‘-’ = 43
                var key = window.Event ? evt.which : evt.keyCode;    
                var chark = String.fromCharCode(key);
                var tempValue = input.value+chark;
                if(key >= 48 && key <= 57){
                    if(filter(tempValue)=== false){
                        return false;
                    }else{       
                        return true;
                    }
                }
                // else{
                //     if(key == 8 || key == 13 || key == 46 || key == 0) {            
                //         return true;              
                //     }else{
                //         return false;
                //     }
                // }
            }
            function filter(__val__){
                var preg = /^([0-9]+\.?[0-9]{0,2})$/; 
                if(preg.test(__val__) === true){
                    return true;
                }else{
                return false;
                }
                
            }
            


        </script>

<script language="Javascript">
	function imprSelec(nombre) {
	  var ficha = document.getElementById(nombre);
	  var ventimp = window.open(' ', 'popimpr');
	  ventimp.document.write( ficha.innerHTML );
	  ventimp.document.close();
	  ventimp.print( );
	  ventimp.close();
	}
	</script>

<script>
    function imprim1(imp1){
    var printContents = document.getElementById('imp1').innerHTML;
            w = window.open();
            w.document.write(printContents);
            w.document.close(); // necessary for IE >= 10
            w.focus(); // necessary for IE >= 10
            w.print();
            // w.close();
            return true;
        }
    </script>
@stop

{{-- @endif --}}