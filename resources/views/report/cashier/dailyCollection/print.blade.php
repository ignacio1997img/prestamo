@extends('layouts.template-print-alt')

@section('page_title', 'Reporte')

@section('content')
    @php
        $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');    
    @endphp

    <table width="100%">
        <tr>
            <td style="width: 20%"><img src="{{ asset('images/icon.png') }}" alt="CAPRESI" width="70px"></td>
            <td style="text-align: center;  width:50%">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    EMPRESA "CAPRESI"<br>
                </h3>
                <h4 style="margin-bottom: 0px; margin-top: 5px">
                    REPORTE DETALLADO DE RECAUDACION DIARIA
                    {{-- Stock Disponible {{date('d/m/Y', strtotime($start))}} Hasta {{date('d/m/Y', strtotime($finish))}} --}}
                </h4>
                <small style="margin-bottom: 0px; margin-top: 5px; font-size: 10px">
                        {{ date('d', strtotime($date)) }} DE {{ strtoupper($months[intval(date('m', strtotime($date)))] )}} DE {{ date('Y', strtotime($date)) }}
                </small>
                <br>
                <small style="font-size: 10px">
                    COBRADO POR: {{strtoupper($agent)}}
                </small>
                <br>
                <small style="font-size: 10px">
                    <b>TOTAL COBRADO Bs.</b> {{ number_format($amount,2, ',', '.') }}
                </small>
            </td>
            <td style="text-align: right; width:30%">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    <div id="qr_code">
                        {!! QrCode::size(80)->generate('Total Cobrado: Bs'.number_format($amount,2, ',', '.').', Cobrado Por: '.$agent.', Recaudado en Fecha '.date('d', strtotime($date)).' de '.strtoupper($months[intval(date('m', strtotime($date)))] ).' de '.date('Y', strtotime($date))); !!}
                    </div>
                    <small style="font-size: 8px; font-weight: 100">Impreso por: {{ Auth::user()->name }} {{ date('d/m/Y H:i:s') }}</small>
                </h3>
            </td>
        </tr>
    </table>
    {{-- <table style="width: 100%; font-size: 8px" border="1" cellspacing="0" cellpadding="4"> --}}
    <table style="width: 100%; font-size: 10px" border="1" class="print-friendly" cellspacing="0" cellpadding="2">

        <thead>
            <tr>
                <th rowspan="2" style="width:5px">N&deg;</th>   
                <th rowspan="2" style="text-align: center">CI</th>
                <th rowspan="2" style="text-align: center">CLIENTE</th>
                <th rowspan="2" style="text-align: center">ATENDIDO POR</th>
                <th colspan="3" style="text-align: center">DETALLE DEL PRESTAMOS</th>
                <th colspan="3" style="text-align: center">DETALLE DE PAGO</th>
            </tr>
            <tr>
                <th style="text-align: center; width:55px">CODIGO</th>
                <th style="text-align: center; width:5px">FECHA DE PRESTAMO</th>
                <th style="text-align: center; width:5px">TOTAL DEL PRESTAMO</th>

                <th style="text-align: center; width:5px">N. TRANS.</th>
                <th style="text-align: center; width:5px">FECHA DE PAGO</th>
                <th style="text-align: center; width:70px">TOTAL PAGADO</th>
            </tr>
        </thead>
        <tbody>
            @php
                $count = 1;
                $total = 0;
            @endphp
            @forelse ($data as $item)
                <tr>
                    <td>{{ $count }}</td>
                    <td><b>CI:</b> {{ $item->ci}}</td>
                    <td>                        
                        {{strtoupper($item->first_name)}} {{ strtoupper($item->last_name1)}} {{ strtoupper($item->last_name2)}}
                    </td>
                    <td>{{ strtoupper($item->name)}}</td>
                    <td style="text-align: center"><b>{{ $item->code}}</b></td>
                    <td style="text-align: center">{{date('d/m/Y', strtotime($item->dateDay))}}</td>
                    <td style="text-align: right">{{ number_format($item->amountTotal, 2, ',', '.') }}</td>
                    <td style="text-align: right">{{ $item->transaction}}</td>
                    <td style="text-align: center">{{date('d/m/Y', strtotime($item->loanDayAgent_fecha))}}</td>
                    <td style="text-align: right">{{ number_format($item->amount,2, ',', '.') }}</td>                              
                                                                            
                </tr>
                @php
                    $count++;                 
                    $total+= $item->amount;                    
                @endphp
            @empty
                <tr style="text-align: center">
                    <td colspan="9">No se encontraron registros.</td>
                </tr>
            @endforelse
            <tr>
                <th colspan="9" style="text-align: right">Total</th>
                <td style="text-align: right"><strong>Bs. {{ number_format($total,2, ',', '.') }}</strong></td>
            </tr>
        </tbody>       
       

    </table>

    <br>
    <br>
    <table width="100%" style="font-size: 9px">
        <tr>
            <td style="text-align: center">
                ______________________
                <br>
                <b>Entregado Por</b><br>
                <b>{{ Auth::user()->name }}</b><br>
                <b>CI: {{ Auth::user()->ci }}</b>
            </td>
            <td style="text-align: center">
                {{-- ______________________
                <br>
                <b>Firma Responsable</b> --}}
            </td>
            <td style="text-align: center">
                ______________________
                <br>
                <b>Recibido Por</b><br>
                <b>................................................</b><br>
                <b>CI: ........................</b>
            </td>
        </tr>
    </table>
    <script>

    </script>

@endsection
@section('css')
    <style>
        table, th, td {
            border-collapse: collapse;
        }
        /* @media print { div{ page-break-inside: avoid; } }  */
          
        table.print-friendly tr td, table.print-friendly tr th {
            page-break-inside: avoid;
        }
          
    </style>
@stop
