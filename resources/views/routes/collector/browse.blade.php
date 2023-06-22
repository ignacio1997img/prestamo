@extends('voyager::master')

@section('page_title', 'Viendo Datos Personales')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="fa-solid fa-motorcycle"></i> Cobradores
                            </h1>
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            {{-- @if (auth()->user()->hasPermission('add_people')) --}}
                                <a href="#" data-toggle="modal" data-target="#collector-modal" class="btn btn-success">
                                    <i class="voyager-plus"></i> <span>Crear</span>
                                </a>
                            {{-- @endif --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-10">
                                <div class="dataTables_length" id="dataTable_length">
                                    <label>Mostrar <select id="select-paginate" class="form-control input-sm">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select> registros</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" id="input-search" class="form-control">
                            </div>
                        </div>
                        <div class="row" id="div-results" style="min-height: 120px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


     {{-- vault create modal --}}
     <form action="{{ route('routes.collector.store', ['route'=>$id]) }}" method="post">
        @csrf
        <div class="modal modal-primary fade" data-backdrop="static" tabindex="-1" id="collector-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <i class="fa-solid fa-motorcycle"></i> Cobradores
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            {{-- <label for="name">Nombre de remitente</label> --}}
                            <small>Cobradores</small>
                            {{-- <input type="text" name="name" class="form-control text" placeholder="Bóveda principal" required /> --}}
                            <select name="user_id" id="user_id" class="form-control select2" required>
                                <option value="" disabled selected>-- Selecciona un tipo --</option>
                                @foreach ($collector as $item)
                                    <option value="{{$item->id}}">{{$item->name}} </option>                                                
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            {{-- <label for="description">Descripción</label> --}}
                            <small>Observacion</small>
                            <textarea name="description" class="form-control text" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default cancel" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-dark ok">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

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

 
@stop

@section('css')
<style>

    /* LOADER 3 */
    
    #loader-3:before, #loader-3:after{
      content: "";
      width: 20px;
      height: 20px;
      position: absolute;
      top: 0;
      left: calc(50% - 10px);
      background-color: #5eaf4a;
      animation: squaremove 1s ease-in-out infinite;
    }
    
    #loader-3:after{
      bottom: 0;
      animation-delay: 0.5s;
    }
    
    @keyframes squaremove{
      0%, 100%{
        -webkit-transform: translate(0,0) rotate(0);
        -ms-transform: translate(0,0) rotate(0);
        -o-transform: translate(0,0) rotate(0);
        transform: translate(0,0) rotate(0);
      }
    
      25%{
        -webkit-transform: translate(40px,40px) rotate(45deg);
        -ms-transform: translate(40px,40px) rotate(45deg);
        -o-transform: translate(40px,40px) rotate(45deg);
        transform: translate(40px,40px) rotate(45deg);
      }
    
      50%{
        -webkit-transform: translate(0px,80px) rotate(0deg);
        -ms-transform: translate(0px,80px) rotate(0deg);
        -o-transform: translate(0px,80px) rotate(0deg);
        transform: translate(0px,80px) rotate(0deg);
      }
    
      75%{
        -webkit-transform: translate(-40px,40px) rotate(45deg);
        -ms-transform: translate(-40px,40px) rotate(45deg);
        -o-transform: translate(-40px,40px) rotate(45deg);
        transform: translate(-40px,40px) rotate(45deg);
      }
    }
    
    
    </style>
@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
    <script>
        var countPage = 10, order = 'id', typeOrder = 'desc';
        $(document).ready(() => {
            list();
            
            $('#input-search').on('keyup', function(e){
                if(e.keyCode == 13) {
                    list();
                }
            });

            $('#select-paginate').change(function(){
                countPage = $(this).val();
               
                list();
            });
        });

        function list(page = 1){
            let id = {{$id}};
            // alert(id);
            // $('#div-results').loading({message: 'Cargando...'});
            var loader = '<div class="col-md-12 bg"><div class="loader" id="loader-3"></div></div>'
            $('#div-results').html(loader);

            let url = '{{ url("admin/routes/collector/ajax/list") }}';
            let search = $('#input-search').val() ? $('#input-search').val() : '';

            $.ajax({
                url: `${url}/${id}/${search}?paginate=${countPage}&page=${page}`,
                type: 'get',
                
                success: function(result){
                $("#div-results").html(result);
            }});

        }

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