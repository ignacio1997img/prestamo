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
                                <i class="voyager-people"></i> Datos Personales
                            </h1>
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            @if (auth()->user()->hasPermission('add_people'))
                            <a href="{{ route('voyager.people.create') }}" class="btn btn-success">
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

{{-- <form name="form_search" id="form-search" action="{{ route('people.import') }}" method="post" enctype="multipart/form-data">

    @csrf
<input type="file" name="file">
<button type="submit">aceptar</button>
</form> --}}

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

    <div class="modal modal-success fade" data-backdrop="static" tabindex="-1" id="patrocinador-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa-solid fa-key"></i> Verificación</h4>
                </div>
                <div class="modal-body">
                        <input type="hidden" id="id">
                        <input type="hidden" id="phone">
                        <input type="hidden" id="name">
                </div>   
                
                <div class="modal-footer">
                    {{-- <form action="#" id="verificacion_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}</form> --}}

                            <div class="text-center" style="text-transform:uppercase">
                                <i class="fa-brands fa-square-whatsapp" style="color: #52ce5f; font-size: 5em;"></i>
                                <br>
                                {{-- <i class="fa-brands fa-square-whatsapp"></i> --}}
                                <p><b>Desea enviar verificacion?</b></p>
                            </div>
                        <input type="submit" class="btn btn-success pull-right delete-confirm"  value="Sí, Enviar">
                    
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <form action="{{route('people.verification')}}" id="form-create-customer" method="POST">
        <div class="modal modal-success fade" data-backdrop="static" tabindex="-1" id="verificar-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-key"></i> Verificación</h4>
                    </div>
                    <div class="modal-body">
                            <input type="hidden" name="id" id="id">
                            <input type="hidden" name="phone" id="phone">
                            <input type="hidden" name="name" id="name">
                            {{-- @php
                                $telefono = $_GET['phone'];
                                
                            @endphp --}}
                    </div>   
                    
                    <div class="modal-footer">
                        {{-- <form action="#" id="verificacion_form" method="POST">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}</form> --}}

                                <div class="text-center" style="text-transform:uppercase">
                                    <i class="fa-brands fa-square-whatsapp" style="color: #52ce5f; font-size: 5em;"></i>
                                    <br>
                                    {{-- <i class="fa-brands fa-square-whatsapp"></i> --}}
                                    <p><b>Desea enviar verificacion?</b></p>
                                </div>
                            <input type="submit" class="btn btn-success pull-right delete-confirm" value="Sí, Enviar">
                        
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>


 
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
            // $('#div-results').loading({message: 'Cargando...'});
            var loader = '<div class="col-md-12 bg"><div class="loader" id="loader-3"></div></div>'
            $('#div-results').html(loader);

            let url = '{{ url("admin/people/ajax/list") }}';
            let search = $('#input-search').val() ? $('#input-search').val() : '';

            $.ajax({
                url: `${url}/${search}?paginate=${countPage}&page=${page}`,
                type: 'get',
                
                success: function(result){
                    $("#div-results").html(result);
                }
            });

        }
        var id=0;
        var phone =0;
        var name ='';
        $('#verificar-modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) //captura valor del data-empresa=""
            id = button.data('id')
            phone = button.data('phone')
            name = button.data('name')
            var modal = $(this)
            
            modal.find('.modal-body #id').val(id)
            modal.find('.modal-body #name').val(name)
            modal.find('.modal-body #phone').val(phone)
        });



        $('#form-create-customer').submit(function(e){
                // alert(1)
                e.preventDefault();
                $('.btn-save-customer').attr('disabled', true);
                $('.btn-save-customer').val('Guardando...');
                $.post($(this).attr('action'), $(this).serialize(), function(data){
                    if(data.customer){
                        toastr.success('Solicitud Enviada', 'Éxito');
                        $(this).trigger('reset');
                    }else{
                        toastr.error(data.error, 'Error');
                    }
                })
                .always(function(){
                    $('.btn-save-customer').attr('disabled', false);
                    $('.btn-save-customer').text('Guardar');
                    // $('#modal-create-customer').modal('hide');
                    $("#verificar-modal").modal('hide');

                });
            });

       
    </script>
@stop