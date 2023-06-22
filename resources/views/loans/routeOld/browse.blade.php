@extends('voyager::master')

@section('page_title', 'Viendo Prestamos')

{{-- @if (auth()->user()->hasPermission('browse_loans')) --}}

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 id="titleHead" class="page-title">
                                <i class="fa-solid fa-route"></i> Rutas <label class="label label-dark">{{$loan->code}}</label>
                            </h1>

                            <a href="{{ route('loans.index') }}" class="btn btn-warning">
                                <i class="fa-solid fa-rotate-left"></i> <span>Volver</span>
                            </a>
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            @if ($loan->status != 'rechazado' && $loan->debt != 0)
                                <a href="#" data-toggle="modal" data-target="#collector-modal" class="btn btn-success">
                                    <i class="voyager-plus"></i> <span>Crear</span>
                                </a>
                            @endif                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">                        
                        <div class="table-responsive">     
                                <table id="dataStyle" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">Rutas</th>
                                            <th style="text-align: center">Observacion Inicio</th>
                                            <th style="text-align: center">Observacion por el cambio</th>
                                            <th style="text-align: center">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($route as $item)
                                            <tr>
                                                <td style="width: 200pt; text-align: center">{{strtoupper($item->route->name)}}</td>
                                                <td style="text-align: center">{{strtoupper($item->observation)}}</td>
                                                <td style="text-align: center">{{strtoupper($item->deleteObservation)}}</td>
                                                <td style="text-align: center">
                                                    @if ($item->status == 1)
                                                        <label class="label label-success">Activo</label>
                                                    @else 
                                                        <label class="label label-danger">Inactivo</label>
                                                    @endif
                                                </td>
                                                
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" style="text-align: center">Sin Datos</td>
                                            </tr>
                                        @endforelse                                   
                                    </tbody>
                                </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



     {{-- vault create modal --}}
     <form action="{{ route('loan-route.store', ['loan'=>$loan->id]) }}" method="post">
        @csrf
        <div class="modal modal-success fade" data-backdrop="static" tabindex="-1" id="collector-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <i class="fa-solid fa-route"></i> Cambio de Rutas
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <small>Rutas</small>
                            <select name="route_id" id="route_id" class="form-control select2" required>
                                <option value="" disabled selected>-- Selecciona un tipo --</option>
                                @foreach ($data as $item)
                                    <option value="{{$item->id}}">{{$item->name}} </option>                                                
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            {{-- <label for="description">Descripción</label> --}}
                            <small>Observación por la nueva ruta</small>
                            <textarea name="observation" class="form-control text" rows="3" required></textarea>
                        </div>
                        <hr>
                        <div class="form-group">
                            {{-- <label for="description">Descripción</label> --}}
                            <small>Observación Por el Cambio de Ruta</small>
                            <textarea name="deleteObservation" class="form-control text" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default cancel" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success ok">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    


   


@stop


@section('css')
    <style>

    
    
    
    </style>
@stop

@section('javascript')


@stop
{{-- @endif --}}