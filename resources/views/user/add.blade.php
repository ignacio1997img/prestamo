@extends('voyager::master')

@section('page_title', 'Usuario')

@if (auth()->user()->hasPermission('add_user'))

    @section('page_header')
        <h1 id="titleHead" class="page-title">
            <i class="fa-solid fa-user"></i> {{$data? 'Editar': 'Crear'}} Usuarios
        </h1>
        <a href="{{ route('user.index') }}" class="btn btn-warning">
            <i class="fa-solid fa-rotate-left"></i> <span>Volver</span>
        </a>
    @stop

    @section('content')
        <div class="page-content edit-add container-fluid">    
            <form id="agent" action="{{ ! $data ? route('user.store') : route('user.update',$data->id) }}" method="POST">
                @if($data)
                    @method('PUT')
                @endif
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <small>CI</small>
                                        <input type="text" name="ci" value="{{$data? $data->ci:''}}" placeholder="CI" class="form-control text" minlength="7" maxlength="15" required>
                                    </div>  
                                    <div class="form-group col-md-5">
                                        <small>Nombre</small>
                                        <input type="text" name="name" value="{{$data? $data->name:''}}" placeholder="Nombre del usuario" class="form-control text" minlength="10" maxlength="50" required>
                                    </div> 
                                    <div class="form-group col-md-4">
                                        <small>Email</small>
                                        <input type="email" name="email" value="{{$data? $data->email:''}}" placeholder="Email del usuario" class="form-control text" required>
                                    </div>   
                                    
                                    
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <small>Contraseña</small>
                                        
                                        <input type="password" id="input-password" name="password" minlength="8" maxlength="15" class="form-control text" @if(!$data) required @endif>
                                        <span class="input-group-addon" style="background:#fff;border:0px;font-size:25px;cursor:pointer;padding:0px;position: relative;bottom:10px" id="btn-verpassword">
                                            <span class="fa fa-eye"></span>
                                        </span>
                                        @if ($data)
                                            <small>Para mentner la misma contraseña deja vacio el</small>                                            
                                        @endif
                                    </div>
                                    <div class="form-group col-md-5">
                                        <small>Rol</small>
                                        <select name="role_id" id="role_id" class="form-control select2" required>
                                            <option value="" disabled selected>-- Selecciona un rol --</option>
                                            @foreach ($role as $item)
                                                <option value="{{$item->id}}" @if($data) {{$data->role_id==$item->id? 'selected':''}} @endif >{{$item->name}}</option>  
                                            @endforeach
                                        </select>
                                    </div>                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" id="btn_submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
                
            </form>              
        </div>
    @stop

    @section('css')
        <style>
            .form-control, .select2-selection, .mce-tinymce {
                border: 1px solid #7a7a7a !important;
                /* color: #f40202; */
                font-weight: bold
            }
        </style>
    @endsection

    @section('javascript')
        <script>
            $(document).ready(function(){
                let ver_pass = false;
                $('#btn-verpassword').click(function(){
                    if(ver_pass){
                        ver_pass = false;
                        $(this).html('<span class="fa fa-eye"></span>');
                        $('#input-password').prop('type', 'password');
                    }else{
                        ver_pass = true;
                        $(this).html('<span class="fa fa-eye-slash"></span>');
                        $('#input-password').prop('type', 'text');
                    }
                });
            });
        </script>
    @stop

@endif