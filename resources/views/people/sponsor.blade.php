@extends('voyager::master')

@section('page_title', 'Crear prestamos')

{{-- @if (auth()->user()->hasPermission('add_contracts') || auth()->user()->hasPermission('edit_contracts')) --}}

    @section('page_header')
        <h1 id="titleHead" class="page-title">
            <i class="fa-solid fa-medal"></i> Patrocinadores de {{$people->first_name}} {{$people->last_name1}} {{$people->last_name2}}
        </h1>
        <a href="{{ route('voyager.people.index') }}" class="btn btn-warning">
            <i class="fa-solid fa-rotate-left"></i> <span>Volver</span>
        </a>
    @stop

    @section('content')
        <div class="page-content edit-add container-fluid">    
            
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-heading"><h6 class="panel-title">Patrocinadores</h6></div>
                            <div class="panel-body">
            <form id="agent" action="{{route('people-sponsor.store', ['id'=>$people->id])}}" method="POST">
            @csrf
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <small>Patrocinador</small>
                                        <select name="sponsor_id" id="sponsor_id" class="form-control select2" required>
                                            <option value="" disabled selected>-- Selecciona un patrocinador --</option>
                                            @foreach ($data as $item)
                                                <option value="{{$item->id}}">{{ $item->first_name }} {{ $item->last_name1 }} {{ $item->last_name2 }}</option>                                            
                                            @endforeach
                                        </select>
                                    </div>                                  
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <small>Observación</small>
                                        <textarea name="observation" id="observation" class="form-control text" required cols="30" rows="3"></textarea>                                        
                                    </div>                                  
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                    </div>
                                </div>
            </form> 
                                <div class="table-responsive">
                                    <table id="dataStyle" class="dataTable table-hover">
                                        <thead>
                                            <tr>
                                                {{-- <th style="text-align: center">Nro&deg;</th> --}}
                                                <th style="text-align: center">Patrocinador</th>
                                                <th style="text-align: center">Telefono</th>
                                                <th style="text-align: center">Observacion</th>
                                                <th style="text-align: center">Estado</th>
                                                <th style="text-align: right">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($sponsor as $item)
                                                <tr>
                                                    {{-- <td style="text-align: center">{{$item->id}}</td> --}}
                                                    <td style="text-align: center">{{$item->people->first_name}} {{$item->people->last_name1}} {{$item->people->last_name2}}</td>
                                                    <td style="text-align: center">{{$item->people->cell_phone}}</td>
                                                    <td style="text-align: center">{{$item->observation}}</td>
                                                    <td style="text-align: center">
                                                        @if ($item->status == 1)
                                                            <label class="label label-success">Activo</label>
                                                        @else
                                                            <label class="label label-danger">Inactivo</label>
                                                        @endif
                                                    </td>
                                                    <td class="no-sort no-click bread-actions text-right">
                                                        @if ($item->status == 0)
                                                            <button title="habilitar" class="btn btn-sm btn-success delete" onclick="habilitarItem('{{ route('people-sponsor.habilitar', ['people' => $people->id, 'sponsor'=>$item->id]) }}')" data-toggle="modal" data-target="#habilitar-modal">
                                                                <i class="fa-solid fa-thumbs-up"></i> <span class="hidden-xs hidden-sm">Habilitar</span>
                                                            </button>
                                                        @endif
                                                        @if ($item->status == 1)
                                                            <button title="inabilitar" class="btn btn-sm btn-warning delete" onclick="inhabilitarItem('{{ route('people-sponsor.inhabilitar', ['people' => $people->id, 'sponsor'=>$item->id]) }}')" data-toggle="modal" data-target="#inhabilitar-modal">
                                                                <i class="fa-solid fa-thumbs-down"></i> <span class="hidden-xs hidden-sm">Desabilitar</span>
                                                            </button>
                                                        @endif
                                                        {{-- @if (auth()->user()->hasPermission('delete_routes')) --}}
                                                            <button title="Borrar" class="btn btn-sm btn-danger delete" onclick="deleteItem('{{ route('people-sponsor.delete', ['people' => $item->people->id, 'sponsor'=>$item->id]) }}')" data-toggle="modal" data-target="#delete-modal">
                                                                <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                                                            </button>
                                                    </td>
                                                </tr>
                                            @endforeach                                        
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>                
                         
        </div>
        <div class="modal modal-danger fade" data-backdrop="static" tabindex="-1" id="delete-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-trash"></i> Desea eliminar el siguiente registro?</h4>
                    </div>
                    <div class="modal-footer">
                        <form action="#" id="delete_form" method="POST">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <input type="hidden" name="id" id="id">
    
                                <div class="text-center" style="text-transform:uppercase">
                                    <i class="voyager-trash" style="color: red; font-size: 5em;"></i>
                                    <br>
                                    
                                    <p><b>Desea eliminar el siguiente registro?</b></p>
                                </div>
                            <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, eliminar">
                        </form>
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal modal-success fade" data-backdrop="static" tabindex="-1" id="habilitar-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-motorcycle"></i> Desea habilitar el siguiente registro?</h4>
                    </div>
                    <div class="modal-footer">
                        <form action="#" id="habilitar_form" method="GET">
                                <div class="text-center" style="text-transform:uppercase">
                                    <i class="fa-solid fa-thumbs-up" style="color: #1abc9c; font-size: 5em;"></i>
                                    <br>
                                    
                                    <p><b>Desea habilitar el siguiente registro?</b></p>
                                </div>
                            <input type="submit" class="btn btn-success pull-right delete-confirm" value="Sí, habilitar">
                        </form>
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="modal modal-warning fade" data-backdrop="static" tabindex="-1" id="inhabilitar-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-motorcycle"></i> Desea Inhabilitar el siguiente registro?</h4>
                    </div>
                    <div class="modal-footer">
                        <form action="#" id="inhabilitar_form" method="GET">
    
                                <div class="text-center" style="text-transform:uppercase">
                                    <i class="fa-solid fa-thumbs-down" style="color: #fabe28; font-size: 5em;"></i>
                                    <br>
                                    
                                    <p><b>Desea inhabilitar el siguiente registro?</b></p>
                                </div>
                            <input type="submit" class="btn btn-warning pull-right delete-confirm" value="Sí, inhabilitar">
                        </form>
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    @stop

    @section('css')
        <style>

        </style>
    @endsection

    @section('javascript')
        <script>
            function deleteItem(url){
                $('#delete_form').attr('action', url);
            }

            function inhabilitarItem(url){
                $('#inhabilitar_form').attr('action', url);
            }

            function habilitarItem(url){
                $('#habilitar_form').attr('action', url);
            }
        </script>
    @stop

{{-- @endif --}}