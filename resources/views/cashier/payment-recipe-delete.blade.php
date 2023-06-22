<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo de pago</title>
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
                <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="80px"></td>
                <td style="text-align: right">
                    <h3 style="margin-bottom: 0px; margin-top: 5px">CAJAS - CAPRESI<br> <small>ANULACIÓN DE RECIBO DE PAGO N&deg; </small> </h3>
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
                        <h2 style="text-align: center">MOTIVO DE LA ANULACIÓN</h2>
                        <p style="text-align: justify; height: 120px; padding: 10px; font-size: 12px"></p>
                        <h2 style="text-align: right"><small>Monto anulado: Bs.</small> </h2>
                    </div>
                </td>
                <td width="30%" style="padding: 0px 10px">
                    <div>
                        <p style="text-align: center; margin-top: 0px"><b><small>GENERADO POR</small></b></p>
                        <br>
                        <p style="text-align: center">.............................................. <br> <small></small> <br> <small></small> <br> <b></b> </p>
                    </div>
                    <div>
                        <p style="text-align: center; margin-top: 0px"><b><small>ANULADO POR</small></b></p>
                        <br>
                        <p style="text-align: center">.............................................. <br> <small></small> <br> <small></small> <br> <b></b> </p>
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