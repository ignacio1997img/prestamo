@extends('voyager::master')

@section('page_title', 'Cierre de Bóveda')
@if (auth()->user()->hasPermission('browse_vaults'))
@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 id="titleHead" class="page-title">
                                <i class="fa-solid fa-lock"></i> Cierre de Bóveda
                            </h1>
                            {{-- <div class="alert alert-info">
                                <strong>Información:</strong>
                                <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                            </div> --}}
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            @if (auth()->user()->hasPermission('close_vaults'))
                                <a href="#" data-toggle="modal" data-target="#close-modal" class="btn btn-danger">
                                    <i class="fa-solid fa-lock"></i> <span>Realizar cierre</span>
                                </a>
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
            <form action="{{ route('vaults.close.store', ['id' => $vault->id]) }}" method="post">
                @csrf
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    @php
                                        $vault_close = [
                                            '200.00' => [
                                                'open' => 0,
                                                'close' => 0
                                            ],
                                            '100.00' => [
                                                'open' => 0,
                                                'close' => 0
                                            ],
                                            '50.00' => [
                                                'open' => 0,
                                                'close' => 0
                                            ],
                                            '20.00' => [
                                                'open' => 0,
                                                'close' => 0
                                            ],
                                            '10.00' => [
                                                'open' => 0,
                                                'close' => 0
                                            ],
                                            '5.00' => [
                                                'open' => 0,
                                                'close' => 0
                                            ],
                                            '2.00' => [
                                                'open' => 0,
                                                'close' => 0
                                            ],
                                            '1.00' => [
                                                'open' => 0,
                                                'close' => 0
                                            ],
                                            '0.50' => [
                                                'open' => 0,
                                                'close' => 0
                                            ],
                                            '0.20' => [
                                                'open' => 0,
                                                'close' => 0
                                            ],
                                            '0.10' => [
                                                'open' => 0,
                                                'close' => 0
                                            ],
                                        ];

                                        // Asignar monto del último cierre a los cortes de billetes de apertura y cierre     PAR5A LOS SEGUNDO EN ADEKLANTE DE LOS CIERRE
                                        if($vault_closure){
                                            // dd(1);
                                            foreach($vault_closure->details as $detail){
                                                // dd($detail->cash_value);
                                                $vault_close[$detail->cash_value]['open'] += $detail->quantity;
                                                $vault_close[$detail->cash_value]['close'] += $detail->quantity;
                                            }
                                        }
                                        // dd($vault_close);

                                        // Agregar o restar a los cortes de billetes de cierre según los movimiento de bóveda para eL PRIMER CIERRE Y LOS DEMAS
                                        if($vault){
                                            // dd(2);
                                            foreach($vault->details as $detail){
                                                foreach($detail->cash as $cash){
                                                    if($detail->type == 'ingreso'){
                                                        $vault_close[$cash->cash_value]['close'] += $cash->quantity;
                                                        
                                                    }else{
                                                        $vault_close[$cash->cash_value]['close'] -= $cash->quantity;
                                                    }
                                                }
                                            }
                                        }
                                        


                                        // dd($vault_close);
                                    @endphp
                                    <table class="table table-bordered" id="dataStyle">
                                        <thead>
                                            <tr>
                                                <th>Corte</th>
                                                <th class="text-right">Cantidad de apertura</th>
                                                <th class="text-right">Subtotal (Bs.)</th>
                                                <th class="text-right">Cantidad de cierre</th>
                                                <th class="text-right">Subtotal (Bs.)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $total_open = 0;
                                                $total_close = 0;
                                            @endphp
                                            @foreach ($vault_close as $title => $value)
                                                <tr>
                                                    <td><b> <img src="{{ asset('images/cash/'.number_format($title, $title >= 1 ? 0 : 1).'.jpg') }}" alt="{{ $title }}" width="50px"> {{ $title }} </b></td>
                                                    <td class="text-right">{{ $value['open'] }}</td>
                                                    <td class="text-right"><b>{{ number_format($title * $value['open'], 2, ',', '.') }}</b></td>
                                                    <td class="text-right">
                                                        {{ $value['close'] }}
                                                        <input type="hidden" name="cash_value[]" value="{{ $title }}" required>
                                                        <input type="hidden" name="quantity[]" value="{{ $value['close'] }}" required>
                                                    </td>
                                                    <td class="text-right">
                                                        <b>{{ number_format($title * $value['close'], 2, ',', '.') }}</b>
                                                    </td>
                                                </tr>
                                                @php
                                                    $total_open += $title * $value['open'];
                                                    $total_close += $title * $value['close'];
                                                @endphp
                                            @endforeach
                                            <tr>
                                                <td colspan="2"><h5>TOTAL</h5></td>
                                                <td><h4 class="text-right">{{ number_format($total_open, 2, ',', '.') }}</h4></td>
                                                <td colspan="2"><h4 class="text-right">{{ number_format($total_close, 2, ',', '.') }}</h4></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal modal-danger fade" data-backdrop="static" tabindex="-1" id="close-modal" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title"><i class="fa-solid fa-lock"></i> Confirmación de cierre</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <small>Observaciones</small>
                                        <textarea name="observations" class="form-control text" rows="5" placeholder="Ingrese en caso de tener alguna observación antes del cierre"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                    <input type="submit" class="btn btn-danger delete-confirm" value="Sí, cerrar">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 id="h3">Movimientos de Bóveda</h3>
                                <div class="row">
                                    @forelse ($vault->details as $detail)
                                        <div class="col-md-12" style="padding: 0px 20px">
                                            <p>
                                                <small>Tipo:</small>
                                                @if ($detail->type == 'ingreso')
                                                    <label class="label label-success">Ingreso</label>
                                                @else
                                                    <label class="label label-danger">Egreso</label>
                                                @endif
                                                <br>
                                                {{-- <b>Nro de chaque: </b> {{ $detail->bill_number ?? 'S/N' }} <br> --}}
                                                <small>Remitente: </small><small><b>{{ $detail->name_sender ?? 'S/N' }}</b></small> <br>
                                                <small>Descripción: </small> <small><b>{{ $detail->description ?? 'S/N' }}</b></small> <br>
                                                <small>Fecha: </small> <small><b>{{ date('d-m-Y H:i:s', strtotime($detail->created_at)) }}</b></small>
                                            </p>
                                            <table class="table table-bordered" id="dataStyle">
                                                <thead>
                                                    <tr>
                                                        <th>Corte</th>
                                                        <th class="text-right">Cantidad</th>
                                                        <th class="text-right">Subtotal (Bs.)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $total = 0;
                                                    @endphp
                                                    @foreach ($detail->cash as $cash)
                                                        <tr>
                                                            <td>{{ $cash->cash_value }}</td>
                                                            <td class="text-right">{{ $cash->quantity }}</td>
                                                            <td class="text-right">{{ number_format($cash->quantity * $cash->cash_value, 2, ',', '.') }}</td>
                                                        </tr>
                                                        @php
                                                            $total += $cash->quantity * $cash->cash_value;
                                                        @endphp
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="2">TOTAL</td>
                                                        <td class="text-right"><h5>{{ number_format($total, 2, ',', '.') }}</h5></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    @empty
                                                    
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')

@stop

@section('javascript')
    <script>
        $(document).ready(function() {

        });
    </script>
@stop


@else
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif