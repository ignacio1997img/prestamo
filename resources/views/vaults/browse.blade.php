@extends('voyager::master')

@section('page_title', 'Viendo Bóveda')
@if (auth()->user()->hasPermission('browse_vaults'))

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-4" style="padding: 0px">
                            <h1 id="titleHead" class="page-title">
                                <i class="fa-solid fa-vault"></i> Bóveda
                            </h1>
                        </div>                       
                        <div class="col-md-8 text-right" style="margin-top: 30px">
                            @if (!auth()->user()->hasRole('admin'))   
                                @if (auth()->user()->hasPermission('print_vaults') && $vault)
                                    <a href="{{ route('vaults.print.status', ['vault' => $vault ? $vault->id : 0]) }}" target="_blank" class="btn btn-default">
                                        <i class="glyphicon glyphicon-print"></i> <span>Imprimir</span>
                                    </a>
                                @endif                                    
                                @if ($vault)
                                    @if ($vault->status == 'activa')
                                        @if (auth()->user()->hasPermission('movements_vaults'))
                                            <a href="#" data-toggle="modal" data-target="#vaults-details-modal" class="btn btn-primary">
                                                <i class="fa-solid fa-money-bill-1-wave"></i> <span>Agregar Ingresos</span>
                                            </a>
                                            <a href="#" data-toggle="modal" data-target="#vaults-egreso-modal" class="btn btn-success">
                                                <i class="fa-solid fa-money-bill-1-wave"></i> <span>Agregar Egresos</span>
                                            </a>
                                        @endif                                    
                                        @if (auth()->user()->hasPermission('close_vaults'))
                                            <a href="{{ route('vaults.close', ['id' => $vault ? $vault->id : 0]) }}" class="btn btn-danger">
                                                <i class="voyager-lock"></i> <span>Cerrar Bóvedas</span>
                                            </a>
                                        @endif                                    
                                    @else
                                        @if (auth()->user()->hasPermission('open_vaults'))
                                            <a href="#" data-toggle="modal" data-target="#vaults-open-modal" class="btn btn-dark">
                                                <i class="voyager-key"></i> <span>Abrir Bóveda</span>
                                            </a>
                                        @endif                                    
                                    @endif
                                @else
                                    @if (auth()->user()->hasPermission('add_vaults'))
                                        <a href="#" data-toggle="modal" data-target="#vaults-create-modal" class="btn btn-danger">
                                            <i class="fa-solid fa-vault"></i> <span>Crear bóveda</span>
                                        </a>
                                    @endif                                
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        @php
                            $cash_value = [
                                '200.00' => 0,
                                '100.00' => 0,
                                '50.00' => 0,
                                '20.00' => 0,
                                '10.00' => 0,
                                '5.00' => 0,
                                '2.00' => 0,
                                '1.00' => 0,
                                '0.50' => 0,
                                '0.20' => 0,
                                '0.10' => 0,
                            ];
                            if($vault){
                                foreach($vault->details as $detail){
                                    foreach($detail->cash as $cash){
                                        if($detail->type == 'ingreso'){
                                            $cash_value[$cash->cash_value] += $cash->quantity;
                                        }else{
                                            $cash_value[$cash->cash_value] -= $cash->quantity;
                                        }
                                    }
                                }
                            }
                        @endphp
                        <h3 id="h3">Detalles de bóveda</h3>
                        <table class="table table-bordered" id="dataStyle">
                            <thead>
                                <tr>
                                    <th>Corte</th>
                                    <th style="text-align: right">Cantidad</th>
                                    <th style="text-align: right">Subtotal (Bs.)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($cash_value as $title => $value)
                                    <tr>
                                        <td> <h4> <img src="{{ asset('images/cash/'.number_format($title, $title >= 1 ? 0 : 1).'.jpg') }}" alt="{{ $title }}" width="60px"> {{ $title }} </h4></td>
                                        <td style="text-align: right">{{ $value }}</td>
                                        <td style="text-align: right"><b>{{ number_format($title * $value, 2, ',', '.') }}</b></td>
                                    </tr>
                                    @php
                                        $total += $title * $value;
                                    @endphp
                                @endforeach
                                <tr>
                                    <td colspan="2"><h4>TOTAL</h4></td>
                                    <td><h3 style="text-align: right">{{ number_format($total, 2, ',', '.') }}</h3></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>




    {{-- vault create modal --}}
    <form action="{{ route('vaults.store') }}" method="post">
        @csrf
        <div class="modal modal-danger fade" data-backdrop="static" tabindex="-1" id="vaults-create-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                       <i class="fa-solid fa-vault"></i> Crear bóveda
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            {{-- <label for="name">Nombre de remitente</label> --}}
                            <small>Nombre de remitente</small>
                            <input type="text" name="name" class="form-control text" placeholder="Bóveda principal" required />
                        </div>
                        <div class="form-group">
                            {{-- <label for="description">Descripción</label> --}}
                            <small>Descripción</small>
                            <textarea name="description" class="form-control text" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default cancel" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger ok">Crear</button>
                    </div>
                </div>
            </div>
        </div>
    </form>


    {{-- vault add register modal --}}
    <form action="{{ route('vaults.details.store', ['id' => $vault ? $vault->id : 0]) }}" method="post">
        @csrf
        {{-- <input type="hidden" name="vault_id" value="{{  }}"> --}}
        <div class="modal fade" tabindex="-1" data-backdrop="static" id="vaults-details-modal" role="dialog">
            <div class="modal-dialog modal-primary modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-money-bill-1-wave"></i> Movimiento de efectivo a bóveda</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-7" style="margin:0px">
                                <div class="panel-body" style="padding-top:0;max-height:500px;overflow-y:auto">
                                    <table class="table table-hover" id="dataStyle">
                                        <thead>
                                            <tr>
                                                <th>Corte</th>
                                                <th>Cantidad</th>
                                                <th>Sub Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="lista_cortes"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-5" style="margin:0px">
                                <h3 id="h3" class="text-muted">Detalles de movimiento <br> <small style="font-size: 12px"><b>Los campos son opcionales y solo se deben rellenar con fines informativos.</b></small></h3><br>
                                
                                <div class="form-group text-center">
                                    <small><input type="radio" name="type" value="ingreso" checked> Ingreso</label></small>
                                </div>
                                <div class="form-group">
                                    <small>Nombre de remitente</small>
                                    <input type="text" name="name_sender" class="form-control text" placeholder="Jhon Doe" />
                                </div>
                                <div class="form-group">
                                    <small>Detalles</small>
                                    <textarea name="description" class="form-control text" rows="4"></textarea>
                                </div>
                                <div class="form-group text-right">
                                    <small><input type="checkbox" class="text" value="1" required> Aceptar y guardar registro de caja</small>
                                </div>
                                <div class="row">
                                    <div class="col-md-6" ><h3 id="h3">TOTAL</h3></div>
                                    <div class="col-md-6">
                                        <h3 class="text-right text-muted" id="label-total">0.00 Bs.</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Registrar ingreso</button>
                    </div>
                </div>
            </div>
        </div>
    </form>



    {{--  para egresos --}}
    @if ($vault)        
    
    <form action="{{ route('vaults.details.store', ['id' => $vault ? $vault->id : 0]) }}" method="post">
        @csrf
        {{-- <input type="hidden" name="vault_id" value="{{  }}"> --}}
        <div class="modal fade" tabindex="-1" data-backdrop="static" id="vaults-egreso-modal" role="dialog">
            <div class="modal-dialog modal-success modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-money-bill-1-wave"></i> Movimiento de efectivo a bóveda</h4>
                    </div>
                    <div class="modal-body">
                        @php
                            $vaults = \App\Models\Vault::with(['details.cash'])->where('status', 'activa')->where('deleted_at', NULL)->where('id', $vault->id)->first();
                            $cash_values = [
                                '200.00' => 0,
                                '100.00' => 0,
                                '50.00' => 0,
                                '20.00' => 0,
                                '10.00' => 0,
                                '5.00' => 0,
                                '2.00' => 0,
                                '1.00' => 0,
                                '0.50' => 0,
                                '0.20' => 0,
                                '0.10' => 0,
                            ];
                            if($vaults){
                                foreach($vaults->details as $detail){
                                    foreach($detail->cash as $cash){
                                        if($detail->type == 'ingreso'){
                                            $cash_values[$cash->cash_value] += $cash->quantity;
                                        }else{
                                            $cash_values[$cash->cash_value] -= $cash->quantity;
                                        }
                                    }
                                }
                            }
                        @endphp
                        <div class="row">
                            <div class="col-md-7" style="margin:0px">
                                <div class="panel-body" style="padding-top:0;max-height:500px;overflow-y:auto">
                                    <table class="table table-hover" id="dataStyle">
                                        <thead>
                                            <tr>
                                                <th>Corte</th>
                                                <th>Cantidad</th>
                                                <th>Sub Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="lista_cortes1"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-5" style="margin:0px">
                                <h3 id="h3" class="text-muted">Detalles de movimiento <br> <small style="font-size: 12px"><b>Los campos son opcionales y solo se deben rellenar con fines informativos.</b></small></h3><br>
                                <div class="form-group text-center">
                                    <small> <input type="radio" name="type" value="egreso" checked>Egreso</small>
                                </div>
                                <div class="form-group">
                                    <small>Nombre de remitente</small>
                                    <input type="text" name="name_sender" class="form-control text" placeholder="Jhon Doe" />
                                </div>
                                <div class="form-group">
                                    <small>Detalles</small>
                                    <textarea name="description" class="form-control text" rows="4"></textarea>
                                </div>
                                <div class="form-group text-right">
                                    <small><input type="checkbox" value="1" required> Aceptar y guardar registro de caja</small>
                                </div>
                                <div class="row">
                                    <div class="col-md-6"><h3 id="h3">TOTAL</h3></div>
                                    <div class="col-md-6">
                                        <h3 class="text-right text-muted" id="label-total1">0.00 Bs.</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Registrar ingreso</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @else
        @php
            $cash_values = [
                                    '200.00' => 0,
                                    '100.00' => 0,
                                    '50.00' => 0,
                                    '20.00' => 0,
                                    '10.00' => 0,
                                    '5.00' => 0,
                                    '2.00' => 0,
                                    '1.00' => 0,
                                    '0.50' => 0,
                                    '0.20' => 0,
                                    '0.10' => 0,
                                ];
        @endphp
    
    @endif

    {{-- vault open modal --}}
    <form action="{{ route('vaults.open', ['id' => $vault ? $vault->id : 0]) }}" method="post">
        @csrf
        <div class="modal modal-primary fade" tabindex="-1" data-backdrop="static" id="vaults-open-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-lock-open"></i> Abrir Bóveda</h4>
                    </div>
                    <div class="modal-body">
                        <b>Al abrir la bóveda aceptas que tienes todos los cortes de billetes mostrados en el detalle de bóveda, ¿Desea continuar?</b>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-dark">Sí, abrir</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
 
@stop

@section('css')
<style>
    #label-total{
        font-size: 23px;
        color: rgb(12, 12, 12);
        font-weight: bold;
    }
    #label-total1{
        font-size: 23px;
        color: rgb(12, 12, 12);
        font-weight: bold;
    }
</style>
@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        $(document).ready(function() {
            let cortes = new Array('200', '100', '50', '20', '10', '5', '2', '1', '0.5', '0.2', '0.1');


            
            cortes.map(function(value){
                
                // alert(1)
                            $('#lista_cortes').append(`<tr>
                                <td><h4><img src="{{ asset('images/cash/${value}.jpg') }}" alt="${value} Bs." width="70px"> ${value} Bs. </h4></td>
                                <td>
                                    <input type="hidden" name="cash_value[]" value="${value}" required>
                                    <input type="number" name="quantity[]" min="0" step="1" style="width:90px" data-value="${value}" class="form-control input-corte" value="0" required>
                                </td>
                                <td><label id="label-${value.replace('.', '')}">0.00 Bs.</label><input type="hidden" class="input-subtotal" id="input-${value.replace('.', '')}"></td>
                            </tr>`);



                            
                            $('#lista_cortes1').append(`<tr>
                                <td><h4><img src="{{ asset('images/cash/${value}.jpg') }}" alt="${value} Bs." width="70px"> ${value} Bs. </h4></td>
                                <td>
                                    <input type="hidden" name="cash_value[]" value="${value}" required>
                                    <input type="number" name="quantity[]" id="input-cash-${value.replace('.', '-')}" min="0" step="1" style="width:80px" data-value="${value}" class="form-control input-corte1" value="0" required>
                                </td>
                                <td><label id="1label-${value.replace('.', '')}">0.00 Bs.</label><input type="hidden" class="input-subtotal1" id="1input-${value.replace('.', '')}"></td>
                            </tr>`);

                            
            });
          
                let vault = JSON.parse('@json($cash_values)');
                            $(`#input-cash-200`).attr('max', vault['200.00']);
                            $(`#input-cash-100`).attr('max', vault['100.00']);
                            $(`#input-cash-50`).attr('max', vault['50.00']);
                            $(`#input-cash-20`).attr('max', vault['20.00']);
                            $(`#input-cash-10`).attr('max', vault['10.00']);
                            $(`#input-cash-5`).attr('max', vault['5.00']);
                            $(`#input-cash-2`).attr('max', vault['2.00']);
                            $(`#input-cash-1`).attr('max', vault['1.00']);
                            $(`#input-cash-0-5`).attr('max', vault['0.50']);
                            $(`#input-cash-0-2`).attr('max', vault['0.20']);
                            $(`#input-cash-0-1`).attr('max', vault['0.10']);
        

            let columns = [
                { data: 'id', title: 'id' },
                { data: 'user', title: 'Registrado por' },
                { data: 'type', title: 'Tipo' },
                { data: 'bill_number', title: 'N&deg; de Cheque', width: "250px" },
                { data: 'name_sender', title: 'Nombre' },
                { data: 'amount', title: 'Monto' },
                { data: 'description', title: 'Descripción' },
                { data: 'date', title: 'Fecha' },
                { data: 'actions', title: 'Acciones', orderable: false, searchable: false },
            ]
            let id = "{{ $vault ? $vault->id : 0 }}";
            customDataTable("{{ url('admin/vaults/ajax/list') }}/"+id, columns);

            $('.input-corte').keyup(function(){
                let corte = $(this).data('value');
                // alert(corte)
                let cantidad = $(this).val() ? $(this).val() : 0;
                // alert(cantidad)
                calcular_subtottal(corte, cantidad);
            });
            $('.input-corte').change(function(){
                let corte = $(this).data('value');
                // alert(corte)
                let cantidad = $(this).val() ? $(this).val() : 0;
                // alert(cantidad)
                calcular_subtottal(corte, cantidad);
            });

            //para los egresos

            $('.input-corte1').keyup(function(){
                let corte = $(this).data('value');
                // alert(corte)
                let cantidad = $(this).val() ? $(this).val() : 0;
                // alert(cantidad)
                calcular_subtottal1(corte, cantidad);
            });
            $('.input-corte1').change(function(){
                let corte = $(this).data('value');
                // alert(corte)
                let cantidad = $(this).val() ? $(this).val() : 0;
                // alert(cantidad)
                calcular_subtottal1(corte, cantidad);
            });
        });

        function calcular_subtottal(corte, cantidad){
            let total = (parseFloat(corte)*parseFloat(cantidad)).toFixed(2);
            $('#label-'+corte.toString().replace('.', '')).text(total+' Bs.');
            $('#input-'+corte.toString().replace('.', '')).val(total);
            calcular_total();
        }

        function calcular_total(){
            let total = 0;
            $(".input-subtotal").each(function(){
                total += $(this).val() ? parseFloat($(this).val()) : 0;
            });
            console.log(total)
            $('#label-total').html('<b>'+(total).toFixed(2)+' Bs.</b>');
        }



        //para los egresos

        function calcular_subtottal1(corte, cantidad){
            let total = (parseFloat(corte)*parseFloat(cantidad)).toFixed(2);
            $('#1label-'+corte.toString().replace('.', '')).text(total+' Bs.');
            $('#1input-'+corte.toString().replace('.', '')).val(total);
            calcular_total1();
        }

        function calcular_total1(){
            let total = 0;
            $(".input-subtotal1").each(function(){
                total += $(this).val() ? parseFloat($(this).val()) : 0;
            });
            console.log(total)
            $('#label-total1').html('<b>'+(total).toFixed(2)+' Bs.</b>');
        }
    </script>
@stop

@else
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif