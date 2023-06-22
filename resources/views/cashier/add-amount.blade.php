@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', 'Abonar Monto a Caja')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-dollar"></i>
                                Abonar Monto a Caja
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            @php
                $vault = \App\Models\Vault::with(['details.cash'])->where('status', 'activa')->where('deleted_at', NULL)->first();
            @endphp
            @if (!$vault)
            <div class="alert alert-warning">
                <strong>Advertencia:</strong>
                <p>No puedes abonar dinero a una caja debido a que no existe un registro de bóveda activo.</p>
            </div>
            @endif
        </div>
    </div>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <form role="form" action="{{ route('cashiers.amount.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="cashier_id" value="{{ $id }}">
                        <input type="hidden" name="vault_id" value="{{ $vault ? $vault->id : 0 }}">
                        <div class="panel-body">
                            <div class="form-group col-md-12">
                                <div class="panel-body" style="padding-top:0;max-height:400px;overflow-y:auto">
                                    <table class="table table-hover">
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
                            <div class="form-group col-md-12">
                                <input type="hidden" name="amount" id="input-total">
                                <h2 class="text-right" id="label-total">0.00</h2>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label" for="description">Detalle</label>
                                <textarea name="description" class="form-control" rows="5" required></textarea>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            @if ($vault)
                            <button type="submit" class="btn btn-primary save">Abonar <i class="voyager-check"></i> </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- <style>
        .form-control, .select2-selection, .mce-tinymce {
            border: 1px solid #c3c5c7 !important;
            color: #555555
        }
    </style> --}}
@stop

@php
    $vault = \App\Models\Vault::with(['details.cash'])->where('status', 'activa')->where('deleted_at', NULL)->first();
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

@section('javascript')
    <script>
        const APP_URL = '{{ url('') }}';
    </script>
    <script src="{{ asset('js/cash_value.js') }}"></script>
    <script>
        $(document).ready(function(){
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
