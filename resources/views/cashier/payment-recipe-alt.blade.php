<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Boleta de pago</title>
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
        #watermark-stamp {
            position: absolute;
            /* opacity: 0.9; */
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
    
    <div >
        <table width="100%">
            <tr>
                <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="80px"></td>
                <td style="text-align: right">
                    <h2 style="margin-bottom: 0px; margin-top: 5px"></h2>
                    <small>Impreso por {{ Auth::user()->name }} - {{ date('d/m/Y H:i:s') }}</small>
                    <br>
                </td>
                <td style="text-align:center; width: 80px">
              <br>
                    <small><b>N&deg; </b></small>
                </td>
            </tr>
        </table>
        <div id="watermark">
            <img src="{{ asset('images/icon.png') }}" height="100%" width="100%" /> 
        </div>
        <table width="100%" border="1" cellpadding="5" style="font-size: 12px">
            <tr>
                <td><b style="font-size: 15px"></b></td>
                <td style="width: 180px"><b>ITEM:</b> </td>
            </tr>
            <tr>
                <td rowspan="2">
                    <b>NOMBRE: </b><br>
                    <b>CI: </b><br>
                    <b>CARGO: </b><br>
                    <b>AFP: </b><br>
                    <b>NUA/CUA: </b><br>
                    <b>MODALIDAD DE CONTRATACIÓN: </b> 
                </td>
                <td valign="top">
                    <b>PERIODO: </b> <br>
                    <b>DÍAS TRABAJADOS: </b><br>
                </td>
            </tr>
            <tr>
                <td>
                    <b>NIVEL SALARIAL: </b> <br>
                    <b>SUELDO MENSUAL: </b><br>
                    <b>SUELDO PARCIAL: </b><br>
                    <b>BONO ANTIGÜEDAD: </b><br>
                    <b>TOTAL GANADO: </b><br>
                </td>
            </tr>
            <tr>
                <td style="text-align: center"><b>DESCUENTOS</b></td>
                <td rowspan="3" valign="bottom" style="text-align: center"><b><small>SELLO Y FIRMA</small></b></td>
            </tr>
            <tr>
                <td>
                    <br>
                    <b>APORTE LABORAL AFP:</b><br>
                    <b>RC IVA:</b><br>
                    <b>MULTAS:</b><br>
                    <b>TOTAL DESCUENTOS:</b>  <br>
                    <br>
                </td>
            </tr>
            <tr>
                <td>
                    <br>
                    <b>LÍQUIDO PAGABLE: </b>
                    <br> <br>
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