<!DOCTYPE html>
<html lang="es">


    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Verification - LoansApp</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Favicon -->
        <?php $admin_favicon = Voyager::setting('admin.icon_image', ''); ?>
        @if($admin_favicon == '')
            <link rel="shortcut icon" href="{{ asset('images/icon.png') }}" type="image/png">
        @else
            <link rel="shortcut icon" href="{{ Voyager::image($admin_favicon) }}" type="image/png">
        @endif
    </head>


    <body>
        <div class="d-flex align-items-center justify-content-center vh-100">
            <div class="text-center">
                <h1 class="display-1 fw-bold"> <span class="text-danger">Capresi</span></h1>
                <p class="fs-3">  Verificación exitosa.</p>
                <p class="lead">
                    {{-- En estos momentos el sistema se encuentra en mantenimiento, por favor intente más tarde. --}}
                    Sus datos personales han sido verificado exitosamente <br> :)

                </p>
                <img src="{{asset('images/people-verificartion.png')}}" width="250" height="200" border="0">
                <br>

                {{-- <a href="{{ url('/') }}" class="btn btn-primary">Volver a intentar</a> --}}
            </div>
        </div>
    </body>


</html>