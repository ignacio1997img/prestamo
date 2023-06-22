@if ($loan)
    <i class="fa-brands fa-square-whatsapp" style="color: #52ce5f; font-size: 3em;"></i>
    <p style="color: black">Enviar a +591-XXXX{{substr($loan->people->cell_phone, -4)}}</p>
    <a href="#" id="label-link" style="display:block" onclick="codeVerification({{$loan->id}}, {{$loan->people->cell_phone}})">Solicitar Codigo</a>

    <input type="text" style="text-align: center"id="codeVerification"  onkeyup="codev({{$loan->id}},{{$loan->people->cell_phone}})" name="code"  maxlength="6" disabled class="form-control" placeholder="XXXXXX">
    <b class="text-danger" id="label-error" style="display:none">Codigo incorrecto..</b>
    <b class="text-success" id="label-success" style="display:none">Información enviada..</b>


    
    {{-- <button type="submit" class="btn"><i class="fa-brands fa-square-whatsapp" style="color: #52ce5f; font-size: 3em;"></i></button> --}}
    {{-- <span data-purecounter-start="0" data-purecounter-end="{{$people}}" data-purecounter-duration="5" class="purecounter"></span> --}}
    
@else
    {{-- <small style="color: brown">Datos no encontrado</small> --}}
    <b class="text-danger" style="display:block; color: brown">Datos no encontrado..</b>

@endif