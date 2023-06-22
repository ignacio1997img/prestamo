@extends('voyager::master')

@section('page_title', 'Ver Caja')

@if (auth()->user()->hasPermission('read_cashiers'))
@section('page_header')
    <h1 class="page-title">
        <i class="voyager-dollar"></i> Viendo Caja 
        <a href="{{ route('cashiers.index') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp;
            Volver a la lista
        </a>
        <div class="btn-group">
            <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                <span class="glyphicon glyphicon-print"></span> Impresión <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                {{-- <li><a href="{{ route('print.open', ['cashier' => $cashier->id]) }}" target="_blank">Apertura</a></li>
                @if ($cashier->status == 'cerrada')
                <li><a href="{{ route('print.close', ['cashier' => $cashier->id]) }}" target="_blank">Cierre</a></li>
                @endif --}}
            </ul>
        </div>
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
                                <h3 class="panel-title">Descripción</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $cashier->title }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Cajero</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $cashier->user->name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Observaciones</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $cashier->observations ?? 'Ninguna' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                    </div>
                </div>
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <h3 id="h3">Cobros Realizados <label class="label label-success">Ingreso</label></h3>
                            <table id="dataStyle" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="text-align: center; width:5%">N&deg; Transacción</th>                                                    
                                        <th style="text-align: center">Código</th>
                                        <th style="text-align: center">Fecha Pago</th>
                                        <th style="text-align: center">Cliente</th>
                                        <th style="text-align: center">Monto Prestado</th>
                                        <th style="text-align: center">Monto Prestado + Interes</th>
                                        <th style="text-align: center">Atendido Por</th>
                                        <th style="text-align: center">Monto Cobrado</th>
                                        <th style="text-align: center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cont = 1;
                                        $total_movements = 0;
                                    @endphp
                                    @forelse ($trans as $item)
                                        <tr>
                                            <td style="text-align: center">{{$item->transaction}}</td>
                                            <td style="text-align: center">{{$item->code}} <br>
                                                @if ($item->deleted_at || $item->eliminado)
                                                    @if ($item->eliminado)
                                                        <label class="label label-danger">Prestamos eliminado</label>
                                                        <label class="label label-success">Transaccion activa</label>                                                        
                                                    @else
                                                        <label class="label label-danger">Transaccion eliminada</label>                                                        
                                                    @endif
                                                @endif
                                            </td>
                                            <td style="text-align: center">
                                                {{date('d/m/Y H:i:s', strtotime($item->created_at))}}<br><small>{{\Carbon\Carbon::parse($item->created_at)->diffForHumans()}}
                                            </td>
                                            <td>
                                                <small>CI:</small> {{$item->ci?$item->ci:'No definido'}} <br>
                                                {{$item->first_name}} {{$item->last_name1}} {{$item->last_name2}}
                                            </td>
                                            <td style="text-align: right"> <small>Bs.</small> {{$item->amountLoan}}</td>
                                            <td style="text-align: right"> <small>Bs.</small> {{$item->amountTotal}}</td>

                                            <td style="text-align: center">{{$item->agentType}} <br> {{$item->name}}</td>
                                            <td style="text-align: right"><small>Bs.</small> {{$item->amount}}</td>
                                            <td style="text-align: right">
                                                {{-- @if(!$item->deleted_at) --}}
                                                @if (!$item->deleted_at )
                                                    <button title="Eliminar transacción" class="btn btn-sm btn-danger delete" onclick="deleteItem('{{ route('cashiers-loan.transaction.delete', ['cashier'=>$cashier->id, 'transaction' => $item->transaction_id]) }}')" data-toggle="modal" data-target="#delete-transacction-modal">
                                                        <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm"></span>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @php
                                            $total_movements+= $item->amount;
                                        @endphp
                                    @empty
                                        <tr>
                                            <td style="text-align: center" valign="top" colspan="4" class="dataTables_empty">No hay datos disponibles en la tabla</td>
                                        </tr>
                                    @endforelse
                                    {{-- @if ($total_movements != 0)
                                        <tr>
                                            <td colspan="7">Total</td>
                                            <td style="text-align: right"> <small>Bs.</small> {{$total_movements}}</td>     
                                        </tr>
                                    @endif --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <h3 id="h3">Prestamos Entregados</h3>
                        <div class="table-responsive">                            
                            <table id="dataStyle" class="table table-bordered table-bordered">
                                <thead>
                                    <tr>
                                        <th>N&deg;</th>
                                        <th>Codigo</th>
                                        <th>Fecha Solicitud</th>
                                        <th>Fecha Entrega</th>
                                        <th>Nombre Completo</th>
                                        <th style="text-align: right">Monto Prestado</th>
                                        <th style="text-align: right">Interes a Cobrar</th>
                                        <th style="text-align: right">Total</th>
                                        {{-- <th style="text-align: right">Acciones</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cont = 1;
                                        $loans=0;
                                        $interes =0;
                                        $total = 0;
                                    @endphp
                                    @foreach ($loan as $item)
                                        <tr>
                                            <td>{{ $cont }}

                                            </td>
                                            <td>
                                                {{ $item->code }}<br>

                                                
                                                @if ($item->deleted_at)
                                                    <label class="label label-danger">Anulado</label>
                                                @else
                                                    @if ($item->amountTotal == $item->debt)
                                                        <label class="label label-primary">No cuenta con pagos</label><br>
                                                    @else
                                                        <label class="label label-success">Cuenta con dias pagados</label><br>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{ $item->date}}</td>
                                            <td>{{ $item->dateDelivered}}</td>
                                            <td>
                                                <small>CI:</small> {{ $item->people->ci}} <br>
                                                <p>{{ $item->people->first_name}} {{ $item->people->last_name1}} {{ $item->people->last_name2}}</p>
                                                
                                            </td>
                                            <td style="text-align: right">
                                                @if ($item->deleted_at)
                                                    <small>Bs.</small> <del>{{ number_format($item->amountLoan, 2, ',', '.') }}</del>
                                                @else
                                                    <small>Bs.</small> {{ number_format($item->amountLoan, 2, ',', '.') }}
                                                @endif
                                            </td>
                                            <td style="text-align: right">
                                                @if ($item->deleted_at)
                                                    <small>Bs.</small>  <del>{{ number_format($item->amountPorcentage, 2, ',', '.') }}</del>
                                                @else
                                                    <small>Bs.</small>  {{ number_format($item->amountPorcentage, 2, ',', '.') }}
                                                @endif
                                            </td>
                                            <td style="text-align: right">
                                                @if ($item->deleted_at)
                                                    <small>Bs.</small>  <del>{{ number_format($item->amountTotal, 2, ',', '.') }}</del>
                                                @else
                                                    <small>Bs.</small> {{ number_format($item->amountTotal, 2, ',', '.') }}
                                                @endif
                                            </td>
                                        </tr>
                                        @php
                                            $cont++;
                                            if (!$item->deleted_at) {
                                                $interes = $interes + $item->amountPorcentage;
                                                $loans = $loans + $item->amountLoan;
                                                $total = $total + $item->amountTotal;
                                            }
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="5" style="text-align: right"><b>TOTAL</b></td>
                                        <td style="text-align: right"><small>Bs.</small> <b>{{ number_format($loans, 2, ',', '.') }}</b></td>
                                        <td style="text-align: right"><small>Bs.</small> <b>{{ number_format($interes, 2, ',', '.') }}</b></td>
                                        <td style="text-align: right"><small>Bs.</small> <b>{{ number_format($total, 2, ',', '.') }}</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-danger fade" data-backdrop="static" tabindex="-1" id="delete-transacction-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> Desea eliminar la transacción?</h4>
                </div>
                <div class="modal-body">
                    <form action="#" id="delete_form" method="POST">
                        {{ csrf_field() }}
                    <div class="form-group">
                        <label for="observation">Motivo</label>
                        <textarea name="observations" class="form-control" rows="5" placeholder="Describa el motivo de la anulación del pago" required></textarea>
                    </div>
                    <label class="checkbox-inline"><input type="checkbox" value="1" required>Confirmar anulación</label>
                </div>
                <div class="modal-footer">
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, eliminar">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <form id="form-delete" action="{{ route('cashiers-loan.delete') }}" method="POST">
        @csrf
        <div class="modal modal-danger fade" tabindex="-1" id="delete_payment-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-trash"></i> Desea anular el siguiente prestamos?</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="cashier_id" value="{{ $cashier->id }}">
                        <input type="hidden" name="loan_id" id="loan_id">

                        <div class="form-group">
                            <label for="observation">Motivo</label>
                            <textarea name="observations" class="form-control" rows="5" placeholder="Describa el motivo de la anulación del pago" required></textarea>
                        </div>
                        <label class="checkbox-inline"><input type="checkbox" value="1" required>Confirmar anulación</label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-danger" value="Sí, ¡anúlalo!">
                    </div>
                </div>
            </div>
        </div>
    </form>


@stop

@section('javascript')
    <script>

        function deleteItem(url){
            $('#delete_form').attr('action', url);
        }

        $(document).ready(function () {
            $('.btn-delete').click(function(){
                let loan_id = $(this).data('id');
                // alert(loan_id)
                $(`#form-delete input[name="loan_id"]`).val(loan_id);
            });
        });

        // function print_recipe(id){
        //     window.open("{{ url('admin/planillas/pagos/print') }}/"+id, "Recibo", `width=700, height=500`)
        // }

        // function print_recipe_delete(id){
        //     window.open("{{ url('admin/planillas/pagos/delete/print') }}/"+id, "Recibo", `width=700, height=500`)
        // }

        // function print_transfer(id){
        //     window.open("{{ url('admin/cashiers/print/transfer/') }}/"+id, "Comprobante de transferencia", `width=700, height=500`)
        // }
    </script>
@stop
@else
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif
