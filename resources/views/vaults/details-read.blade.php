@extends('voyager::master')

@section('page_title', 'Detalles de Movimiento de Bóveda')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-treasure"></i> Detalles de Movimiento de Bóveda
        <a href="{{ route('vaults.index') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp;
            Volver a la lista
        </a>
        @if($details->type == 'ingreso')
            <a href="{{ route('vaults.print.vault.details', ['id' => $details->id]) }}" target="_blank" title="Imprimir" class="btn btn-sm btn-danger view">
                <i class="glyphicon glyphicon-print"></i> <span class="hidden-xs hidden-sm">Imprimir</span>
            </a>
        @elseif($details->type == 'egreso' && $details->cashier_id)
            {{-- <a href="{{ route('print.open', ['cashier' => $details->cashier_id]) }}" target="_blank" title="Imprimir" class="btn btn-sm btn-danger view">
                <i class="glyphicon glyphicon-print"></i> <span class="hidden-xs hidden-sm">Imprimir</span>
            </a> --}}
        @endif
    </h1>
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Registrado por</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $details->user->name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Tipo</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $details->type }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">N&deg; de Cheque</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $details->bill_number ?? 'No definido' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Nombre de remitente</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $details->name_sender ?? 'No definido' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Descripción</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{!! $details->description ?? 'No hay descripción' !!}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">
                                    Detalles
                                    @if ($details->deleted_at)
                                        <label class="label label-danger">Eliminado</label>
                                    @endif
                                </h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Corte</th>
                                            <th>Cantidad</th>
                                            <th class="text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total = 0;
                                        @endphp
                                        @foreach ($details->cash as $item)
                                        @php
                                            $total += $item->cash_value * $item->quantity;
                                        @endphp
                                            <tr>
                                                <td><img src="{{ asset('images/cash/'.number_format($item->cash_value, $item->cash_value < 1 ? 1 : 0, '.', '.').'.jpg') }}" alt="{{ $item->cash_value }} Bs." width="70px"> {{ $item->cash_value }} Bs.</td>
                                                <td>{{ number_format($item->quantity, 0) }}</td>
                                                <td class="text-right">{{ number_format($item->cash_value * $item->quantity, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="2">TOTAL</td>
                                            <td class="text-right"><h4>Bs. {{ number_format($total, 2, ',', '.') }}</h4></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <hr style="margin:0;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $(document).ready(function () {
            
        });
    </script>
@stop
