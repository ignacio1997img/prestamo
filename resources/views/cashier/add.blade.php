@extends('voyager::master')

@php
    $url = $_SERVER['REQUEST_URI'];
    $url_array = explode('/', $url);
    $cashier = null;
    if($url_array[count($url_array)-1] == 'edit'){
        $id = $url_array[count($url_array)-2];
        $cashier = \App\Models\Cashier::findOrFail($id);
    }
@endphp

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop


@section('page_title', $cashier ? 'Editar Caja' : 'Añadir Caja')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 id="titleHead" class="page-title">
                                <i class="voyager-dollar"></i>
                                {{ $cashier ? 'Editar Caja' : 'Añadir Caja' }}
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            @php
                $vault = \App\Models\Vault::with(['details.cash'])->where('status', 'activa')->where('deleted_at', NULL)->where('id', $vault->id)->first();
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
           
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    @if ($cashier)
                        <h1 class="text-center">La opción de editar caja no está disponible</h1>
                    @else
                    <form role="form" action="{{ route('cashiers.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="vault_id" value="{{ $vault ? $vault->id : 0 }}">
                        <div class="panel-body">
                            @if (!$vault)
                                <div class="alert alert-warning">
                                    <strong>Advertencia:</strong>
                                    <p>No puedes aperturar caja debido a que no existe un registro de bóveda activo.</p>
                                </div>
                            @endif
                            <div class="col-md-6">
                                <div class="panel-body" style="padding-top:0;max-height:400px;overflow-y:auto">
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
                            <style>
                                #label-total{
                                    font-size: 28px;
                                    color: rgb(12, 12, 12);
                                    font-weight: bold;
                                }
                            </style>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="form-group">
                                        {{-- <label class="control-label" for="user_id">Cajero</label> --}}
                                        <small>Cajero</small>
                                        <select name="user_id" class="form-control select2" required>
                                            <option value="">Seleccione al usuario</option>
                                            @foreach (\App\Models\User::where('role_id', '!=', 1)->get() as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        {{-- <label class="control-label" for="title">Nombre de la caja</label> --}}
                                        <small>Nombre de la caja</small>
                                        <input type="text" name="title" placeholder="Nombre de la Caja o Cobrador" class="form-control text" placeholder="Caja 1" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="amount" id="input-total">
                                        <h2 class="text-right" id="label-total">0.00</h2>
                                    </div>
                                    <div class="form-group">
                                        {{-- <label class="control-label" for="observations">Observaciones</label> --}}
                                        <small>Observaciones</small>
                                        <textarea name="observations" class="form-control text" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            @if ($vault)
                            <button type="submit" class="btn btn-primary save">Guardar <i class="voyager-check"></i> </button>
                            @endif
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
@stop

@section('javascript')
    <script>
        const APP_URL = '{{ url('') }}';
    </script>
    <script src="{{ asset('js/cash_value.js') }}"></script>
    <script>
        
        // $(".select2").css("background-color", "#8e00ff ");
        $(document).ready(function(){
            $(".select2").select2({theme: "classic"});

            let vault = JSON.parse('@json($cash_value)');
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
        });
    </script>
@stop
