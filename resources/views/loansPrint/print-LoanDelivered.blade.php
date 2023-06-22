{{-- impresion para cuando la persona entrega el prestamo al cliente --}}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Comprobante de entrega de prestamo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body{
            /* margin: 100px auto; */
            font-family: Arial, sans-serif;
            /* font-weight: 100; */
            max-width: 1000px;
        }
        /* #watermark {
            position: absolute;
            opacity: 0.1;
            z-index:  -1000;
        }
        #watermark-stamp {
            position: absolute;
            z-index:  -1000;
        }
        #watermark img{
            position: relative;
            width: 300px;
            height: 300px;
            left: 205px;
        }
        #watermark-stamp img{
            position: relative;
            width: 4cm;
            height: 4cm;
            left: 50px;
            top: 70px;
        } */
        .show-print{
            display: none;
            padding-top: 15px
        }
        .btn-print{
            padding: 5px 10px
        }
        @media print{
            .hide-print, .btn-print{
                display: none
            }
            .show-print, .border-bottom{
                display: block
            }
            .border-bottom{
                border-bottom: 1px solid rgb(90, 90, 90);
                padding: 20px 0px;
            }
        }
    </style>
</head>
<body>
    <div class="hide-print" style="text-align: right; padding: 10px 0px">
        <button class="btn-print" onclick="window.close()">Cancelar <i class="fa fa-close"></i></button>
        <button class="btn-print" onclick="window.print()"> Imprimir <i class="fa fa-print"></i></button>
    </div>
    

        <table width="90%">
            <tr>
                <td colspan="2" style="text-align: center">
                    <h3 style="margin-bottom: 0px; margin-top: 50px; font-size: 22px"><small>COMPROBANTE DE ENTREGA <br> DE PRESTAMO</small> </h3>
                </td>
            </tr>
        </table>
        <hr>
        {{-- <div id="watermark">
            <img src="{{ asset('images/icon.png') }}" height="100%" width="100%" /> 
        </div> --}}
        <table width="90%" cellpadding="5" style="font-size: 15px">
            <tr>
                <th style="text-align: right; width: 10%">
                    CODIGO:
                </th>
                <td>
                    {{ $loan->code }}
                </td>
            </tr>
            <tr>
                <th style="text-align: right; width: 10%">
                    FECHA:
                </th>
                <td>
                    {{Carbon\Carbon::parse($loan->dateDelivered)->format('d/m/Y')}}
                    {{-- {{ date('d/m/Y H:i:s') }} --}}
                </td>
            </tr>
            <tr>
                <th style="text-align: right; width: 10%">
                    BENEFICIARIO:
                </th>
                <td>
                    {{$loan->people->last_name1}} {{$loan->people->last_name2}} {{$loan->people->first_name}}
                </td>
            </tr>
            <tr>
                <th style="text-align: right; width: 10%">
                    CI:
                </th>
                <td>
                    {{$loan->people->ci? $loan->people->ci:'No definido'}}
                </td>
            </tr>
        </table>
        {{-- <hr> --}}
        <table width="90%">
            <tr>
                <td colspan="2" style="text-align: center">
                    <h3 style="margin-bottom: 0px; margin-top: 0px; font-size: 20px"><small>DETALLE DEL PRESTAMO</small> </h3>
                </td>
            </tr>
        </table>

        <table width="90%" cellpadding="2" cellspacing="0" border="0" style="font-size: 15px">
            <tr style="text-align: center">
                <th class="border" style="width: 50%">
                    FECHA INICIO
                </th>

                <th class="border" style="width: 50%">
                    FECHA FIN
                </th>  
            </tr>
                <tr>
                    <td style="text-align: center">
                        {{Carbon\Carbon::parse($loan->loanDay->first()->date)->format('d/m/Y')}}
                    </td>
                    <td style="text-align: center">
                        {{Carbon\Carbon::parse($loan->loanDay->last()->date)->format('d/m/Y')}}
                        {{-- <b>Bs.</b> {{number_format($loan->amountPorcentage, 2, ',', '.')}} --}}
                    </td>    
                </tr>            
        </table>
        <table width="90%" cellpadding="2" cellspacing="0" border="0" style="font-size: 15px">
            <tr style="text-align: center">
                <th class="border" style="width: 33%">
                    MONTO PRESTADO
                </th>

                <th class="border" style="width: 33%">
                    INTERES A PAGAR
                </th>             
                <th class="border" style="width: 44%">
                    TOTAL A PAGAR
                </th>
            </tr>
            @php
                $total=0;
            @endphp
                <tr>
                    <td style="text-align: right">
                        <b>Bs.</b> {{number_format($loan->amountLoan, 2, ',', '.')}}
                    </td>
                    <td style="text-align: right">
                        <b>Bs.</b> {{number_format($loan->amountPorcentage, 2, ',', '.')}}
                    </td>
                    <td style="text-align: right">
                        <b>Bs.</b> {{number_format($loan->amountTotal, 2, ',', '.')}}
                        {{-- {{Carbon\Carbon::parse($item->date)->format('d/m/Y')}} --}}
                    </td>                    
                   
                </tr>
            
        </table>
        
        {{-- <hr> --}}
        <table width="90%">
            <tr>
                <td colspan="2" style="text-align: center">
                    <h3 style="margin-bottom: 0px; margin-top: 5px; font-size: 20px"><small>ENTREGADO POR</small> </h3>
                </td>
            </tr>
        </table>
        <table width="90%" cellpadding="2" cellspacing="0" border="0" style="font-size: 15px">
            <tr>
                <td style="text-align: right; width: 40%">
                    {{strtoupper($loan->agentDelivered->role->name)}}:
                </td>
                <td style="text-align: center; width: 60%">
                    {{strtoupper($loan->agentDelivered->name)}}
                </td>
            </tr>
        </table>
        <hr>
        <table width="90%" cellpadding="5" style="font-size: 12px">
            <tr>
                <th style="text-align: right; width: 10%">
                    FIRMA:
                </th>
                <td>
                    _____________________________________
                </td>
            </tr>
            <tr>
                <th style="text-align: right; width: 10%">
                    NOMBRE:
                </th>
                <td>
                    _____________________________________
                </td>
            </tr>
            <tr>
                <th style="text-align: right; width: 10%">
                    CI:
                </th>
                <td>
                    _____________________________________
                </td>
            </tr>
        </table>
        <br><br>
        <table width="90%" style="font-size: 12px">
            <tr style="text-align: center">
                <td>
                        <small style="font-size: 10px; font-weight: 100">Impreso por: {{ Auth::user()->name }} {{ date('d/M/Y H:i:s') }}</small>
                        <br>
                    <small><b>LOANSAPP V1</b></small>
                </td>
            </tr>
        </table>

    <style>
        .border{
            border: solid 1px black;
        }
    </style>
<script type="text/javascript" src="{{ voyager_asset('js/app.js') }}"></script>

    <script>
        $(function() {
            // alert(1);
            window.print();
        
        });
        document.body.addEventListener('keypress', function(e) {
            switch (e.key) {
                case 'Enter':
                    window.print();
                    break;
                case 'Escape':
                    window.close();
                default:
                    break;
            }
        });
    </script>
</body>
</html>