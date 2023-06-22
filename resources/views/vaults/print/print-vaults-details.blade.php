<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ Str::ucfirst($detail->type) }} de bóveda</title>
    <link rel="shortcut icon" href="{{ asset('images/icon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body{
            margin: 0px auto;
            font-family: Arial, sans-serif;
            font-weight: 100;
            max-width: 740px;
        }
        #watermark {
            position: absolute;
            opacity: 0.1;
            z-index:  -1000;
        }
        #watermark img{
            position: relative;
            width: 300px;
            height: 300px;
            left: 205px;
        }
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
    <div style="height: 45vh">
        <table width="100%">
            <tr>
                <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="80px"></td>
                <td style="text-align: right">
                    <h3 style="margin-bottom: 0px; margin-top: 5px">
                        CAJAS - CAPRESI<br> <small>{{ Str::upper($detail->type) }} A BÓVEDA </small> <br>
                        <small style="font-size: 11px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/M/Y H:i:s') }}</small>
                    </h3>
                </td>
            </tr>
        </table>
        <div id="watermark">
            <img src="{{ asset('images/icon.png') }}" style="margin-top: 150px;" height="100%" width="100%" />
        </div>
        @if ($detail->deleted_at)
            <div style="text-align: center">
                <h1 style="color: red">ELIMINADO</h1>
            </div>
        @endif

        <hr style="margin: 0px">
        <table width="100%" cellpadding="10" style="font-size: 12px">
            <tr>
                <td colspan="2">
                    <table width="100%" cellpadding="10">
                        <tr>
                            <td><b>Registrado por: </b></td>
                            <td>{{ $detail->user->name }}</td>
                            <td><b>N&deg; de cheque: </b></td>
                            <td>{{ $detail->bill_number ?? 'S/N' }}</td>
                            <td><b>Nombre del remitente: </b></td>
                            <td>{{ $detail->name_sender ?? 'No definido' }}</td>
                        </tr>
                        <tr>
                            <td><b>Descripción: </b></td>
                            <td colspan="5">{!! $detail->description ?? 'No hay descripción' !!}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="70%">
                    <div>
                        <h3>Detalles de {{ $detail->type }}</h3>
                        <table width="100%">
                            <thead>
                                <tr>
                                    <th>Corte</th>
                                    <th style="text-align:right">Cantidad</th>
                                    <th style="text-align:right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($detail->cash as $item)
                                @php
                                    $total += $item->cash_value * $item->quantity;
                                @endphp
                                    <tr>
                                        <td><img src="{{ asset('images/cash/'.number_format($item->cash_value, $item->cash_value < 1 ? 1 : 0, '.', '.').'.jpg') }}" alt="{{ $item->cash_value }} Bs." width="70px"> {{ $item->cash_value }} Bs.</td>
                                        <td style="text-align:right">{{ number_format($item->quantity, 0) }}</td>
                                        <td style="text-align:right">{{ number_format($item->cash_value * $item->quantity, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="2">TOTAL</td>
                                    <td style="text-align:right"><h4>Bs. {{ number_format($total, 2, ',', '.') }}</h4></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>
                <td width="30%" style="padding: 0px 10px">
                    <div>
                        <p style="text-align: center; margin-top: 0px"><b><small>RECIBIDO POR</small></b></p>
                        <br>
                        @php
                            $user_chief = App\Models\User::with('role')->where('role_id', 2)->where('deleted_at', NULL)->first();
                        @endphp
                        <p style="text-align: center">.............................................. <br> <small>{{ $user_chief ? $user_chief->name : '' }}</small> <br> <small>{{ $user_chief ? $user_chief->ci : '' }}</small> <br> <b>{{ $user_chief ? strtoupper($user_chief->role->display_name) : '' }}</b> </p>
                    </div>
                    <div>
                        <p style="text-align: center; margin-top: 0px"><b><small>ENTREGADO POR</small></b></p>
                        <br>
                        <p style="text-align: center">.............................................. <br> <small>{{ strtoupper($detail->user->name) }}</small> <br> <small>{{ $detail->user->ci }}</small> <br> <b>{{ strtoupper($detail->user->role->display_name) }}</b> </p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <script>
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