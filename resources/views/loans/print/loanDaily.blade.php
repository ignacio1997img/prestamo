@extends('layouts.template-print-legal')

@section('page_title', 'Contrato de prestamo diario')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');    
@endphp

@section('qr_code')
    <div id="qr_code">
        {{-- {!! QrCode::size(100)->generate(Request::url()); !!} --}}
        {!! QrCode::size(80)->generate('Contrato:'.$loan->id.', DEUDOR '.$loan->people->first_name.' '.$loan->people->last_name1.' '.$loan->people->last_name2.' con C.I. '.$loan->people->ci.', con un prestamo de '.number_format($loan->amountTotal, 2, ',', '.').' Bs. Santisima Trinidad, '.date('d', strtotime($loan->date)) .' de '. $months[intval(date('m', strtotime($loan->date)))] .' de '. date('Y', strtotime($loan->date)) ); !!}
    </div>
@endsection
{{-- <div class="visible-print text-center">
    {!! QrCode::size(100)->generate(Request::url()); !!}
    <p>Scan me to return to the original page.</p>
</div> --}}

@section('content')
    <div class="content" style="text-align: justify">
        <h2 class="text-center" style="font-size: 18px">SEÑOR NOTARIO DE FE PÚBLICA</h2>
        <p><em>En los registros de escritura que corren a su digno cargo, sírvase insertar una de </em><em><strong>PRESTAMOS DE DINERO</strong></em><em> suscrita al tenor de las siguientes cláusulas: </em></p>
        <p><em><span style="text-decoration: underline;"><strong>PRIMERA .- </strong></span></em><em><strong>(ANTECEDENTES).- (DE LAS PARTES).-</strong></em><em> Intervienen en la suscripción del presente contrato.</em></p>
        <p><em>1.1.- </em><em><strong>CHRISTIAN MERCADO PERICON</strong></em><em>, mayor de edad, con</em><em><strong> C.I.1919784 BE, </strong></em><em>hábil por derecho, quien en adelante se denomina </em><em><strong>ACREEDOR.</strong></em></p>
        <p><em>1.2.- </em><em><strong style="text-transform: uppercase;">{{$loan->people->first_name}} {{$loan->people->last_name1}} {{$loan->people->last_name2}}</strong></em> <em>hábil en toda forma de derecho con </em>  <em><b>C.I:{{ $loan->people->ci }}</b> con telefono {{$loan->people->cell_phone}} con domicilio <b>{{$loan->people->zone}}</b>, <b>{{$loan->people->street}}, {{$loan->people->home}}</b></em> <em> quien en adelante se denomina <strong>DEUDOR</strong>.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>SEGUNDA .- </strong></span></em><em><strong>(OBJETO).- </strong></em><em> Al presente el <strong>DEUDOR</strong> declara haber recibido del <strong>ACREEDOR</strong> la suma de <strong>Bs. {{NumerosEnLetras::convertir($loan->amountTotal,'Bolivianos',true)}}</strong> en calidad de préstamo.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>TERCERA .- </strong></span></em><em><strong>(DEL PAZO E INTERES).- </strong></em><em> La tasa de interés MENSUAL convenida entre las partes será fijada en el convencional máximo permitido por ley establecido en el Art.- 409 del Código Civil.</em></p>
        <p><em>El plazo convenido entre partes para la devolución de la suma mencionada en la cláusula segunda del presente contrato MAS LOS INTERESES GENERADOS EN EL PLAZO ESTABLECIDO es de UN MES a partir de la firma contrato.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CUARTA .- </strong></span></em><em><strong>(DE LA GARANTIA).- </strong></em><em><strong>EL DEUDOR</strong> garantiza el cumplimiento de la obligación, con todos sus bienes habidos y por haber, presentes y futuros, sin reserva de exclusión alguna.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>QUINTA .- </strong></span></em><em><strong>(DE LA MORA Y EJECUCION).- </strong></em><em>En caso de incumplimiento en el pago de la obligación e interés convenido por parte del <strong>DEUDOR</strong>,  en los plazos acordados, caerá automáticamente en mora la obligación sin necesidad de requerimiento Judicial o extrajudicial alguno. Si no fuera protocolizado este documento, surtirá los efectos de instrumento público al solo reconocimiento de firmas y rubricas por ante cualquier Notario de FE Publica o Autoridad competente.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>SEXTA .- </strong></span></em><em><strong>(INCUMPLIMIENTO).- </strong></em><em>También se deja expresa y claramente establecido, que a los efectos de una acción judicial que inicie EL <strong>ACREEDOR</strong> por incumplimiento en el pago del capital e interés, pactado en el presente documento yo: <strong>{{$loan->people->first_name}} {{$loan->people->last_name1}} {{$loan->people->last_name2}}</strong> en mi calidad de <strong>DEUDOR</strong> de forma libre, voluntaria y espontánea, autorizo se proceda con orden judicial o sin ella al descuento del 20% de mi sueldo o pensión que percibo a donde corresponda al momento de la ejecución del presente documento, hasta el monto total a pagar con capital, intereses moratorios, gastos judiciales y extrajudiciales, honorarios Profesionales y otros que por ley correspondan.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>SEPTIMA .- </strong></span></em><em><strong>(CONFORMIDAD).- </strong></em><em>En señal de conformidad con todas y cada una de las clausulas tanto <strong>EL ACREEDOR</strong> como <strong>EL DEUDOR</strong> firman al pie del presente documento en señal de conformidad.</em></p>
        <p style="text-align: right;">
            <span>Santísima Trinidad</span>, {{ date('d', strtotime($loan->date)) }} de {{ $months[intval(date('m', strtotime($loan->date)))] }} de {{ date('Y', strtotime($loan->date)) }}</p>
     
        <table width="100%" style="text-align: center; margin-top: 1px;">
            <tr>
                <td style="width: 50%">
                    ....................................................... <br>
                    <em>CHRISTIAN MERCADO PERICÓN</em> <br>
                    <b>C.I.1919784 Beni</b><br>
                    {{-- <b>ACREEDOR</b> --}}
                </td>
                <td style="width: 50%">
                    ....................................................... <br>
                    {{-- <em>{{ $loan->people->gender == 'masculino' ? 'Sr.' : 'Sra.' }} {{$loan->people->first_name}} {{$loan->people->last_name1}} {{$loan->people->last_name2}}</em><br> --}}
                    <em>{{$loan->people->first_name}} {{$loan->people->last_name1}} {{$loan->people->last_name2}}</em><br>
                    <b>C.I:{{$loan->people->ci}}</b><br>
                    {{-- <b>DEUDOR</b> --}}
                </td>                
            </tr>

            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <br><br><br>
            @if ($loan->guarantor_id)
                <tr>
                    <td colspan="2" style="width: 50%">
                        ....................................................... <br>
                        {{-- <em>{{ $loan->people->gender == 'masculino' ? 'Sr.' : 'Sra.' }} {{$loan->people->first_name}} {{$loan->people->last_name1}} {{$loan->people->last_name2}}</em><br> --}}
                        <em>{{$loan->guarantor->first_name}} {{$loan->guarantor->last_name1}} {{$loan->guarantor->last_name2}}</em><br>
                        <b>C.I:{{$loan->guarantor->ci}}</b><br>
                        <b>GARANTE</b>
                    </td>                
                </tr>
            @endif
        </table>
        
       



    </div>
@endsection

@section('css')
    <style>
        .content {
            padding: 50px 34px;
            font-size: 15px;
        }
        .text-center{
            text-align: center;
        }
        .saltopagina{
            display: none;
        }
        @media print{
            .saltopagina{
                display: block;
                page-break-before: always;
            }
            .pt{
                height: 90px;
            }
        }
    </style>
@endsection

@section('script')
    <script>

    </script>
@endsection