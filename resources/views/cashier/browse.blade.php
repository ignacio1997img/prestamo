@extends('voyager::master')

@section('page_title', 'Viendo Caja')
@if (auth()->user()->hasPermission('browse_cashiers'))

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="fa-regular fa-money-bill-1"></i> Cajeros
                            </h1>
                            {{-- <div class="alert alert-info">
                                <strong>Información:</strong>
                                <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                            </div> --}}
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            @if ( !auth()->user()->hasRole('admin') && $vault)
                                <a href="{{ route('cashiers.create') }}" class="btn btn-success">
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
                            @if ($vault)          
                                <table id="dataStyle" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">Id</th>
                                            <th style="text-align: center">Usuario</th>
                                            <th style="text-align: center">Título</th>
                                            <th style="text-align: center">Estado</th>
                                            <th style="text-align: center">Apertura</th>
                                            <th style="text-align: center">Cierre</th>
                                            <th style="text-align: right">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($cashier as $item)
                                            <tr>
                                                <td>{{$item->id}}</td>
                                                <td style="width: 200pt; text-align: center">{{strtoupper($item->user->name)}}</td>
                                                <td style="text-align: center">{{strtoupper($item->title)}}</td>
                                                <td style="text-align: center">
                                                    @if ($item->status == 'abierta')
                                                        <label class="label label-success">Abierta</label>
                                                    @endif
                                                    @if ($item->status == 'cerrada')
                                                        <label class="label label-danger">Cerrada</label>
                                                    @endif

                                                    @if ($item->status == 'cierre pendiente')
                                                        <label class="label label-primary">Cierre Pendiente</label>
                                                    @endif

                                                    @if ($item->status == 'apertura pendiente')
                                                        <label class="label label-warning">Apertura Pendiente</label>
                                                    @endif
                                                    {{-- <label class="label label-success">{{$item->status}}</label> --}}

                                                </td>
                                                <td style="text-align: center">{{date('d/m/Y H:i:s', strtotime($item->created_at))}}<br><small>{{\Carbon\Carbon::parse($item->created_at)->diffForHumans()}}.</small></td>
                                                <td style="text-align: center">@if($item->closed_at){{date('d/m/Y H:i:s', strtotime($item->closed_at))}}<br><small>{{\Carbon\Carbon::parse($item->closed_at)->diffForHumans()}}.@endif </small></td>
                                
                                                <td style="text-align: right">


                                                    <div class="btn-group" style="margin-right: 3px">
                                                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                                                            Mas <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu" role="menu" style="left: -90px !important" >
                                                            @php
                                                                $x=0;
                                                            @endphp
                                                            @foreach ($item->vault_detail as $aux)
                                                                @php
                                                                    $x++;
                                                                @endphp
                                                                <li><a href="#" onclick="openWindow({{$aux->id}})" class="btn-rotation" style="color: blue"  data-toggle="modal" title="Imprimir Comprobante" ><i class="fa-solid fa-print"></i> {{$x==1?'Imprimir Comporbante de Apertura':'Imprimir Comporbante de Abono #'.$x}}</a></li>
                                                            @endforeach
                                                            @if ($item->status == 'cerrada')
                                                                <li><a href="#" onclick="closeWindow({{$item->id}})" class="btn-rotation" style="color: red" data-toggle="modal" title="Imprimir Comprobante de Cierre" ><i class="fa-solid fa-print"></i> Imprimir Comprobante de Cierre</a></li>
                                                            @endif                
                                                        </ul>
                                                    </div>





                                                    @if ($item->status == 'abierta')
                                                        <a href="{{route('cashiers.amount', ['cashier'=>$item->id])}}" title="Editar" class="btn btn-sm btn-success">
                                                            <i class="voyager-dollar"></i> <span class="hidden-xs hidden-sm">Abonar Dinero</span>
                                                        </a>
                                                    @endif
                                                    @if (auth()->user()->hasPermission('read_cashiers'))
                                                        <a href="{{route('cashiers.show', ['cashier'=>$item->id])}}" title="Editar" class="btn btn-sm btn-warning">
                                                            <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                                        </a>
                                                    @endif
                                                    
                                         
                                                    {{-- @if ($item->status == 'cerrada')
                                                        
                                                        <a href="#" title="Imprimir" class="btn btn-dark" onclick="closeWindow({{$item->id}})">
                                                            <i class="glyphicon glyphicon-print"></i> <span class="hidden-xs hidden-sm">Imprimir cierre</span>
                                                        </a>

                                                    @endif --}}

                                                    @if ($item->status == "cierre pendiente")
                                                        <a href="{{route('cashiers.confirm_close',['cashier' => $item->id])}}" title="Ver" class="btn btn-sm btn-primary pull-right">
                                                            <i class="voyager-lock"></i> <span class="hidden-xs hidden-sm">Confirmar Cierre de Caja</span>
                                                        </a>
                                                    @endif
                                                    {{-- <div class="no-sort no-click bread-actions text-right"> --}}
                                                        {{-- @if(auth()->user()->hasPermission('read_income'))
                                                            
                                                            <a href="{{route('income_view',$item->id)}}" title="Ver" target="_blank" class="btn btn-sm btn-info view">
                                                                <i class="voyager-file-text"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                                            </a>                                                                
                                                        @endif
                                                        @if($item->condicion == 1)
                                                            @if(auth()->user()->hasPermission('edit_income'))
                                                                <a href="" title="Editar" class="btn btn-sm btn-warning">
                                                                    <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                                                                </a>
                                                            @endif
                                                            @if(auth()->user()->hasPermission('delete_income'))
                                                                <button title="Anular" class="btn btn-sm btn-danger delete" data-toggle="modal" data-id="{{$item->id}}" data-target="#myModalEliminar">
                                                                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Anular</span>
                                                                </button>
                                                            @endif
                                                        @endif
                                                    </div> --}}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" style="text-align: center">Sin Datos</td>
                                            </tr>
                                        @endforelse                                   
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-warning">
                                    <strong>Advertencia:</strong>
                                    <p>No se encuentra disponible porque no existe un registro de bóveda creada.</p>
                                </div>
                            @endif
                        </div>
                    </div>
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
    <script>

        $(function()
        {
            $('#dataStyle').DataTable({
                    language: {
                            // "order": [[ 0, "desc" ]],
                            sProcessing: "Procesando...",
                            sLengthMenu: "Mostrar _MENU_ registros",
                            sZeroRecords: "No se encontraron resultados",
                            sEmptyTable: "Ningún dato disponible en esta tabla",
                            sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                            sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                            sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                            sSearch: "Buscar:",
                            sInfoThousands: ",",
                            sLoadingRecords: "Cargando...",
                            oPaginate: {
                                sFirst: "Primero",
                                sLast: "Último",
                                sNext: "Siguiente",
                                sPrevious: "Anterior"
                            },
                            oAria: {
                                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                            },
                            buttons: {
                                copy: "Copiar",
                                colvis: "Visibilidad"
                            }
                        },
                        order: [[ 0, 'desc' ]],
            });
            
         
        })




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
            // $('#div-results').loading({message: 'Cargando...'});
            var loader = '<div class="col-md-12 bg"><div class="loader" id="loader-3"></div></div>'
            $('#div-results').html(loader);


            let url = '{{ url("admin/people/ajax/list") }}';
            let search = $('#input-search').val() ? $('#input-search').val() : '';
            $.ajax({
                url: `${url}/${search}?paginate=${countPage}&page=${page}`,
                type: 'get',
                success: function(response){
                    $('#div-results').html(response);
                    // $('#div-results').loading('toggle');

                }
            });
        }

        function openWindow(id){
            // window.open("{{ url('admin/cashiers/print/transfer') }}/"+id, "Entrega de fondos", `width=700, height=400`);
            
            // $url = route('print.open', ['cashier' => id]);
            // alert(id)
            window.open("{{ route('print.open')}}/"+id, 'Apertura de caja', `width=1000, height=700`);
        }


        function closeWindow(id){
            window.open("{{ route('print.close')}}/"+id, 'Apertura de caja', `width=1000, height=700`);
        }

        // @if(session('rotation_id'))
        //     let rotation_id = "{{ session('rotation_id') }}";
        //     window.open(`{{ url('admin/people/rotation') }}/${rotation_id}`, '_blank').focus();
        // @endif
    </script>
@stop

@else
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif