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
                    REPORTE DETALLADO DE PRESTAMO ENTREGADOS DIARIOS
                    {{-- Stock Disponible {{date('d/m/Y', strtotime($start))}} Hasta {{date('d/m/Y', strtotime($finish))}} --}}
                </h4>
                <small style="margin-bottom: 0px; margin-top: 5px">
                        {{ date('d', strtotime($date)) }} de {{ $months[intval(date('m', strtotime($date)))] }} de {{ date('Y', strtotime($date)) }}
                    </small>
            </td>
            <td style="text-align: right; width:30%">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    <div id="qr_code">
                        {!! QrCode::size(80)->generate('Total Dinero Prestado: Bs'.number_format($amount,2, ',', '.').', Entregado Por: '.$agent.', Entregado en Fecha '.date('d', strtotime($date)).' de '.strtoupper($months[intval(date('m', strtotime($date)))] ).' de '.date('Y', strtotime($date))); !!}
                    </div>
                    <small style="font-size: 8px; font-weight: 100">Impreso por: {{ Auth::user()->name }} {{ date('d/M/Y H:i:s') }}</small>
                </h3>
            </td>
        </tr>
    </table>
    <table style="width: 100%; font-size: 8px" border="1" cellspacing="0" cellpadding="4">
        <thead>
            <tr>
                <th>N&deg;</th>
                <th>Codigo</th>
                <th>Fecha Entrega</th>
                <th>CI.</th>
                <th>Nombre Completo</th>
                <th>Entregado Por</th>
                <th style="text-align: right">Monto Prestado</th>
                <th style="text-align: right">Interes a Cobrar</th>
                <th style="text-align: right">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;

                $loans=0;
                $interes =0;
                $total = 0;
            @endphp
            @forelse ($data as $item)

                <tr>
                    <td style="text-align: center">{{ $cont }}</td>
                    <td style="text-align: center">
                        <b>{{ $item->code }}</b>
                    </td>
                    <td style="text-align: center">{{ date("d-m-Y", strtotime($item->dateDelivered))}}</td>
                    <td>
                        <b>CI:</b> {{ $item->people->ci}} <br>
                    </td>
                    <td>{{ strtoupper($item->people->first_name)}} {{ strtoupper($item->people->last_name1)}} {{ strtoupper($item->people->last_name2)}}
                    </td>
                    <td>
                        {{ strtoupper($item->agentDelivered->name)}}
                    </td>
                    <td style="text-align: right">
                        {{ number_format($item->amountLoan, 2, ',', '.') }}
                    </td>
                    <td style="text-align: right">
                         {{ number_format($item->amountPorcentage, 2, ',', '.') }}
                    </td>
                    <td style="text-align: right">
                        {{ number_format($item->amountTotal, 2, ',', '.') }}
                    </td>
                    
                </tr>
                @php
                    $cont++;
                        
                    $interes = $interes + $item->amountPorcentage;
                    $loans = $loans + $item->amountLoan;
                    $total = $total + $item->amountTotal;
                @endphp
                
            @empty
                <tr style="text-align: center">
                    <td colspan="9">No se encontraron registros.</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="6" style="text-align: right"><b>TOTAL</b></td>
                <td style="text-align: right"><b>Bs. {{ number_format($loans, 2, ',', '.') }}</b></td>
                <td style="text-align: right"><b>Bs. {{ number_format($interes, 2, ',', '.') }}</b></td>
                <td style="text-align: right"><b>Bs. {{ number_format($total, 2, ',', '.') }}</b></td>
            </tr>
        </tbody>     
       

    </table>

    <br>
    <br>
    <table width="100%" style="font-size: 9px"  >
        <tr>
            <td style="text-align: center" >
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
                <b>CI: ....................</b>
            </td>
        </tr>
    </table>

@endsection

@section('css')
    <style>
        table, th, td {
            border-collapse: collapse;
        }
          
    </style>
@stop