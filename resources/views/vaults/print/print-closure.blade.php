<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cierre de bóveda</title>
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
    @for ($i = 0; $i < 2; $i++)
    <div style="height: 45vh" @if ($i == 1) class="show-print" @else class="border-bottom" @endif>
        <table width="100%">
            <tr>
                <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="120px"></td>
                <td style="text-align: right">
                    <h3 style="margin-bottom: 0px; margin-top: 5px">
                        CAJAS - GOBERNACIÓN<br> <small>CIERRE DE BÓVEDA </small> <br>
                        <small style="font-size: 11px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/M/Y H:i:s') }}</small>
                    </h3>
                </td>
            </tr>
        </table>
        <div id="watermark">
            <img src="{{ asset('images/icon.png') }}" height="100%" width="100%" /> 
        </div>

        <hr style="margin: 0px">
        <table width="100%" cellpadding="10" style="font-size: 12px">
            <tr>
                <td width="70%">
                    <div>
                        @php
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
                            if($closure){
                                // dd($closure);
                                foreach($closure->details as $detail){
                                    $cash_value[$detail->cash_value] += $detail->quantity;
                                }
                            }
                        @endphp
                        <h3>Detalles de cierre de bóveda</h3>
                        <table width="100%">
                            <thead>
                                <tr>
                                    <th>Corte</th>
                                    <th style="text-align: right">Cantidad</th>
                                    <th style="text-align: right">Subtotal (Bs.)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($cash_value as $title => $value)
                                    <tr>
                                        <td>
                                            <b>
                                                <img src="{{ asset('images/cash/'.number_format($title, $title >= 1 ? 0 : 1).'.jpg') }}" alt="{{ $title }}" width="30px">
                                                &nbsp; {{ $title }}
                                            </b>
                                        </td>
                                        <td style="text-align: right">{{ $value }}</td>
                                        <td style="text-align: right"><b>{{ number_format($title * $value, 2, ',', '.') }}</b></td>
                                    </tr>
                                    @php
                                        $total += $title * $value;
                                    @endphp
                                @endforeach
                                <tr>
                                    <td colspan="2"><h4>TOTAL</h4></td>
                                    <td><h3 style="text-align: right">{{ number_format($total, 2, ',', '.') }}</h3></td>
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
                        <p style="text-align: center">.............................................. <br> <small>{{ strtoupper($closure->user->name) }}</small> <br> <small>{{ $closure->user->ci }}</small> <br> <b>{{ strtoupper($closure->user->role->display_name) }}</b> </p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    @endfor
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