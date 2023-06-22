@extends('voyager::master')

@section('page_title', 'Crear agente')

{{-- @if (auth()->user()->hasPermission('add_contracts') || auth()->user()->hasPermission('edit_contracts')) --}}

    @section('page_header')
        <h1 class="page-title">
            <i class="fa-solid fa-person-digging"></i> Editar Agente
        </h1>
    @stop

    @section('content')
        <div class="page-content edit-add container-fluid">    
            <form id="agent" action="{{route('agents.update', ['agent' => $agent->id])}}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-heading"><h6 class="panel-title">Datos del Agente a Registrar</h6></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="type_id">Tipo de agente</label>
                                        <select name="type_id" id="type_id" class="form-control select2" required>
                                            <option value="" disabled selected>-- Selecciona un tipo --</option>
                                            @foreach ($type as $item)
                                                <option value="{{$item->id}}"{{$agent->agentType_id==$item->id? 'selected':''}}>{{$item->name}}</option>                                                
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="people_id">Persona</label>
                                        <select name="people_id" class="form-control select2" required>
                                            <option value="" disabled selected>-- Selecciona a la persona --</option>
                                            @foreach ($people as $item)
                                                <option value="{{$item->id}}"{{$agent->people_id==$item->id? 'selected':''}}>{{$item->last_name}} {{$item->first_name}}</option>                                                
                                            @endforeach
                                        </select>
                                    </div>                                    
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="observation">Observación</label>
                                        <textarea name="observation" id="observation" class="form-control" cols="10" rows="3">{{$agent->observation}}</textarea>
                                    </div>                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="row">
                    <div class="col-md-12 div-hidden">
                        <div class="panel panel-bordered">
                            <div class="panel-heading"><h6 class="panel-title">Datos de complementarios</h6></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="preventive_number">N&deg; de preventivo</label>
                                        <input type="text" name="preventive_number" value="" class="form-control">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="organizational_source">Fuente organizacional</label>
                                        <input type="text" name="organizational_source" value="" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 div-hidden div-5">
                        <div class="panel panel-bordered">
                            <div class="panel-heading"><h6 class="panel-title">Datos de complementarios</h6></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="details_work">Funciones generales</label>
                                        <textarea class="form-control richTextBox" name="details_work">
                                           
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>              
        </div>
    @stop

    @section('css')
        <style>

        </style>
    @endsection

    @section('javascript')
        <script>
           
        </script>
    @stop

{{-- @endif --}}