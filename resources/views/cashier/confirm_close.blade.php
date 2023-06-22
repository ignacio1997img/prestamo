@extends('voyager::master')

@section('page_title', 'Confimar cierre de caja')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-lock"></i> Confimar cierre de caja
                            </h1>
                        </div>
                        <div class="col-md-4" style="margin-top: 30px">
                            
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
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="row">
                        <div class="col-md-6">
                            <form name="form_close" action="{{ route('cashiers.confirm_close.store', ['cashier' => $cashier->id]) }}" method="post">
                                @csrf
                                <table id="dataStyle" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Corte</th>
                                            <th>Cantidad</th>
                                            <th>Sub Total</th>
                                        </tr>
                                    </thead>
                                    @php
                                        $cash = ['200', '100', '50', '20', '10', '5', '2', '1', '0.5', '0.2', '0.1'];
                                        $missing_amount = 0;
                                    @endphp
                                    <tbody>
                                        @foreach ($cash as $item)
                                        <tr>
                                            <td><h4 style="margin: 0px"><img src=" {{ url('images/cash/'.$item.'.jpg') }} " alt="{{ $item }} Bs." width="70px"> {{ $item }} Bs. </h4></td>
                                            <td>
                                                @php
                                                    $details = $cashier->details->where('cash_value', $item)->first();
                                                @endphp
                                                {{ $details ? $details->quantity : 0 }}
                                            </td>
                                            <td>
                                                {{ $details ? number_format($details->quantity * $item, 2, ',', '.') : 0 }}
                                                <input type="hidden" name="cash_value[]" value="{{ $item }}">
                                                <input type="hidden" name="quantity[]" value="{{ $details ? $details->quantity : 0 }}">
                                            </td>
                                            @php
                                            if($details){
                                                $missing_amount += $details->quantity * $item;
                                            }
                                            @endphp
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                {{-- confirm modal --}}
                                <div class="modal modal-danger fade" tabindex="-1" id="close_modal" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title"><i class="voyager-lock"></i> Confirme que desea cerrar la caja?</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p>Esta acción cerrará la caja y no podrá realizar modificaciones posteriores</p>
                                                <div class="form-group">
                                                    <label for="">Bóveda</label>
                                                    <select name="vault_id" class="form-control select2">
                                                        @foreach (\App\Models\Vault::where('status', 'activa')->where('user_id', auth()->user()->id)->get() as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small>Elija la bóveda en la que se va a guardar el dinero</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-danger">Sí, cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @php
                            $cashier_in = $cashier->movements->where('type', 'ingreso')->where('deleted_at', NULL)->sum('amount');
                            $cashier_balance = $cashier->movements->where('type', 'ingreso')->where('deleted_at', NULL)->sum('balance');

                            // $sub = \App\Models\Adition::where('cashier_id', $cashier->id)
                            //                             ->where('deleted_at', null)
                            //                             ->sum('cant');
                            $sub =0;
                            $movements = $cashier_in + $sub;
                            $total = $movements;
                        @endphp
                        <div class="col-md-6" style="padding-top: 20px">
                            <div class="row">
                                <div class="col-md-6">
                                    <p style="margin-top: 20px">Dinero Asignado a caja por el Administrador</p>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="text-right" style="padding-right: 20px">{{ number_format($cashier_in, 2, ',', '.') }}</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p style="margin-top: 20px">Dinero disponible en Caja</p>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="text-right" style="padding-right: 20px">{{ number_format($cashier_balance, 2, ',', '.') }}</h3>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <p style="margin-top: 20px">Total a enviar bóveda</p>
                                </div>
                                <div class="col-md-6">
                                    <div class="panel-heading" style="border-bottom:0;">
                                        <h3 class="text-right" style="padding-right: 20px">{{ number_format($cashier_balance, 2, ',', '.') }}</h3>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-danger btn-block btn-confirm" data-toggle="modal" data-target="#close_modal">Cerrar caja <i class="voyager-lock"></i></button>
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
        const APP_URL = '{{ url('') }}';
    </script>
    <script src="{{ asset('js/cash_value.js') }}"></script>
    <script>
        $(document).ready(function() {

        });
    </script>
@stop
