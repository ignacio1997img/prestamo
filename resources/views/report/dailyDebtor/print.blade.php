@extends('layouts.template-print-alt')

@section('page_title', 'Reporte')

@section('content')
    @php
        $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');    
    @endphp

    <table width="100%">
        <tr>
            <td style="width: 20%"><img src="{{ asset('images/icon.png') }}" alt="CAPRESI" width="70px"></td>
            <td style="text-align: center;  width:70%">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    EMPRESA "CAPRESI"<br>
                </h3>
                <h4 style="margin-bottom: 0px; margin-top: 5px">
                    REPORTE DETALLADO DE DEUDORES ATRAZADOS
                    {{-- Stock Disponible {{date('d/m/Y', strtotime($start))}} Hasta {{date('d/m/Y', strtotime($finish))}} --}}
                </h4>
                <small style="margin-bottom: 0px; margin-top: 5px">
                        {{ date('d') }} de {{ $months[intval(date('m'))] }} de {{ date('Y') }}
                   
                </small>
            </td>
            <td style="text-align: right; width:30%">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                   
                    <small style="font-size: 11px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/M/Y H:i:s') }}</small>
                </h3>
            </td>
        </tr>
    </table>
    <br><br>
    <table style="width: 100%; font-size: 12px" border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th rowspan="2" style="width:5px">N&deg;</th>
                <th rowspan="2" style="text-align: center">CODIGO</th>
                <th rowspan="2" style="text-align: center">FECHA SOLICITUD</th>
                <th rowspan="2" style="text-align: center">FECHA ENTREGA</th>
                <th rowspan="2" style="text-align: center">CI</th>
                <th rowspan="2" style="text-align: center">CLIENTE</th>
                <th rowspan="2" style="text-align: center">CELULAR</th>
                <th rowspan="2" style="text-align: center">DIRECCION</th>
                <th rowspan="2" style="text-align: center">PAGO DIARIO</th>
                <th rowspan="2" style="text-align: center">TOTAL DIAS A PAGAR</th>
                <th rowspan="2" style="text-align: center">MONTO PRESTADO</th>
                <th rowspan="2" style="text-align: center">INTERES A PAGAR</th>
                <th rowspan="2" style="text-align: center">TOTAL A PAGAR</th>
                <th colspan="2" style="text-align: center">RETRAZO</th>
            </tr>
            <tr>
                <th style="text-align: center">DIAS</th>
                <th style="text-align: center">TOTAL A PAGAR</th>
            </tr>
        </thead>
        <tbody>
            @php
                $count = 1;
                $dia =0;
                $pagar =0;
            @endphp
            @forelse ($data as $item)
                <tr style="text-align: center">
                            <td>{{ $count }}</td>
                            <td style="text-align: center">{{ $item->code}}</td>
                            <td style="text-align: center">{{date('d/m/Y', strtotime($item->date))}}</td>
                            <td style="text-align: center">{{date('d/m/Y', strtotime($item->dateDelivered))}}</td>
                            <td style="text-align: center">{{ $item->ci }}</td>
                            <td style="text-align: left">{{ $item->last_name1}} {{ $item->last_name2}} {{ $item->first_name}}</td>
                            <td style="text-align: center">{{ $item->cell_phone}}</td>
                            <td style="text-align: left">{{ $item->street}} <br>
                                {{ $item->home}} <br>
                                {{ $item->zone}}
                            </td>
                            <td style="text-align: right"><b>Bs.{{ number_format($item->amountTotal/$item->day,2) }}</b></td>
                            <td style="text-align: right">{{ $item->day }}</td>
                            <td style="text-align: right">{{ number_format($item->amountLoan,2) }}</td>
                            <td style="text-align: right">{{ number_format($item->amountPorcentage,2) }}</td>
                            <td style="text-align: right">{{ number_format($item->amountTotal,2) }}</td>
                            <td style="text-align: right; background-color: #ff7979">{{ $item->diasAtrasado }}</td>
                            <td style="text-align: right; background-color: #ff7979">{{ number_format($item->montoAtrasado,2) }}</td>      
                </tr>
                @php
                    $count++;    
                    $dia+=$item->diasAtrasado;                     
                    $pagar+=$item->montoAtrasado;                     
                @endphp                        
            @empty
                <tr style="text-align: center">
                    <td colspan="15">No se encontraron registros.</td>
                </tr>
            @endforelse
            <tr>
                <th colspan="13" style="text-align: left">Total</th>
                <td style="text-align: right"><strong>{{$dia }}</strong></td>
                <td style="text-align: right"><strong>Bs. {{ number_format($pagar,2) }}</strong></td>
            </tr>
        </tbody>
       
    </table>

@endsection