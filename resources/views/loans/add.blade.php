@extends('voyager::master')

@section('page_title', 'Crear prestamos')

@if (auth()->user()->hasPermission('add_loans'))

    @section('page_header')
        <h1 id="titleHead" class="page-title">
            <i class="fa-solid fa-hand-holding-dollar"></i> Crear Prestamos
        </h1>
        <a href="{{ route('loans.index') }}" class="btn btn-warning">
            <i class="fa-solid fa-rotate-left"></i> <span>Volver</span>
        </a>
    @stop

    @section('content')
        <div class="page-content edit-add container-fluid">    
            <form id="agent" action="{{route('loans.store')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-heading">
                                <h5 id="h4" class="panel-title">Detalle del Prestamos &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    {{-- <br> --}}
                                    {{-- <div class="col-md-12 text-right"> --}}
                                        <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="diario" checked><p>Prestamo diario</p></label>
                                        <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="diarioespecial"><p>Prestamo Diario Especial</p> </label>
                                    {{-- </div> --}}
                                </h5>
                            </div>
                            <div class="panel-body">
                                @if (!$cashier)  
                                    <div class="alert alert-warning">
                                        <strong>Advertencia:</strong>
                                        <p>No puedes crear un nuevo prestamo debido a que no tiene una caja asignada.</p>
                                    </div>
                                @else     
                                    @if ($cashier->status != 'abierta')
                                        <div class="alert alert-warning">
                                            <strong>Advertencia:</strong>
                                            <p>No puedes crear un nuevo prestamo debido a que no tiene una caja activa.</p>
                                        </div>
                                    @endif
                                @endif
                                <div class="row">
                                    <div class="form-group col-md-2">
                                        <small>Fecha</small>
                                        <input type="date" name="date" class="form-control text" required>
                                    </div>   
                                    <div class="form-group col-md-6">
                                        <small>Asignar Ruta</small>
                                        <select name="route_id" id="route_id" class="form-control select2" required>
                                            <option value="" disabled selected>-- Selecciona una ruta --</option>
                                            @foreach ($routes as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>  
                                            @endforeach
                                        </select>
                                    </div>                                  
                                </div>


                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <small for="customer_id">Beneficiario del Prestamo</small>
                                        {{-- <div class="input-group"> --}}
                                            <select name="people_id" class="form-control" id="select_people_id" required></select>

                                            {{-- <span class="input-group-btn">

                                                <button class="btn btn-primary" title="Nueva persona" data-target="#modal-create-customer" data-toggle="modal" style="margin: 0px" type="button">
                                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                                </button>
                                            </span> --}}
                                        {{-- </div> --}}
                                    </div>
                                    <div class="form-group col-md-6">
                                        <small for="customer_id">Garante</small>
                                        {{-- <div class="input-group"> --}}
                                            <select name="guarantor_id" class="form-control" id="select_guarantor_id"></select>
                                            {{-- <span class="input-group-btn">
                                                <button class="btn btn-primary" title="Nueva persona" data-target="#modal-create-customer" data-toggle="modal" style="margin: 0px" type="button">
                                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                                </button>
                                            </span> --}}
                                        {{-- </div> --}}
                                    </div>
                                    {{-- <div class="form-group col-md-6">
                                        <small>Beneficiario del Prestamo</small>
                                        <select name="people_id" id="people_id" class="form-control select2" required>
                                            <option value="" disabled selected>-- Selecciona un tipo --</option>
                                            @foreach ($people as $item)
                                                <option @if($item->status == 'entregado' && $item->debt > 0 ) disabled @endif value="{{$item->id}}" >{{$item->last_name1}} {{$item->last_name2}} {{$item->first_name}}</option>                                                
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <small>Asignar Garante</small>
                                        <select name="guarantor_id" id="guarantor_id" class="form-control select2">
                                            <option value="" disabled selected>-- Seleccionar un garante --</option>
                                            @foreach ($people as $item)
                                                <option value="{{$item->id}}">{{$item->last_name1}} {{$item->last_name2}} {{$item->first_name}}</option>  
                                            @endforeach
                                        </select>
                                    </div>                                     --}}
                                </div>
                                <input type="hidden" name="type" id="text_type">
                                <div class="row">
                                    <div class="form-group col-md-2">
                                        <small>Monto a Prestar (Bs.)</small>
                                        <input type="number" name="amountLoan" id="amountLoan" style="text-align: right" value="0" min="1" step=".01" onkeypress="return filterFloat(event,this);" onchange="subTotal()" onkeyup="subTotal()" class="form-control text" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <small>Dias Total A Pagar</small>
                                        <input type="number" min="1" id="day1" value="24" style="text-align: right" disabled onkeypress="return filterFloat(event,this);" onchange="diasPagar()" onkeyup="diasPagar()" class="form-control text" required>
                                        <input type="hidden" min="1" name="day" id="day" onkeypress="return filterFloat(event,this);" value="24" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <small>Interes Prestamos (%)</small>
                                        <input type="number" id="porcentage1" min="0" step="any" style="text-align: right" disabled value="20" onkeypress="return filterFloat(event,this);" onchange="porcentagePagar()" onkeyup="porcentagePagar()" onchange="subTotal()" onkeyup="subTotal()" class="form-control text" required>
                                        <input type="hidden" name="porcentage" id="porcentage" onkeypress="return filterFloat(event,this);" value="20" class="form-control" required>
                                    </div>    
                                    <div class="form-group col-md-2">
                                        <small>Interes a Pagar (Bs.)</small>
                                        <input type="number" id="amountPorcentage1" min="0" step="any" style="text-align: right" disabled value="0" onkeypress="return filterFloat(event,this);" onchange="porcentageAmount()" onkeyup="porcentageAmount()" onchange="subTotal()" onkeyup="subTotal()" class="form-control text" required>
                                        <input type="hidden" name="amountPorcentage" id="amountPorcentage" onkeypress="return filterFloat(event,this);" value="0" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <small>Pago Diario (Bs.)</small>
                                        <input type="text" id="amountDay1" style="text-align: right" disabled value="0" class="form-control text">
                                        <input type="hidden" name="amountDay" id="amountDay"onkeypress="return filterFloat(event,this);" value="0" class="form-control">
                                        <b class="text-danger" id="label-amount" style="display:none">Incorrecto..</b>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <small>Total a Pagar (Bs.)</small>
                                        <input type="number" id="amountTotal1" style="text-align: right" disabled value="0" class="form-control text">
                                        <input type="hidden" name="amountTotal" id="amountTotal" value="0" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        {{-- <label for="observation"></label> --}}
                                        <small>Observación</small>
                                        <textarea name="observation" id="observation" class="form-control text" cols="30" rows="5"></textarea>
                                    </div>                                  
                                </div>
                                @if ($cashier)    
                                    @if ($cashier->status == 'abierta')

                                        <input type="hidden" name="cashier_id" value="{{$cashier->id}}">
                                        <div class="row">
                                            <div class="col-md-12 text-right">
                                                <button type="submit" id="btn_submit" class="btn btn-primary">Guardar</button>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
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

            $(document).ready(function(){
                $('#agent').submit(function(e){
                    $('#btn_submit').text('Guardando...');
                    $('#btn_submit').attr('disabled', true);

                });
            })

            $(document).ready(() => {
                $(`#text_type`).val($(".radio-type:checked").val());
                $('.radio-type').click(function(){
                    $(`#text_type`).val($(".radio-type:checked").val());
                    list();
                    
                });
            });
            function list()
            {
                let type = $(".radio-type:checked").val();
              
                if(type=='diario')
                {
                    $('#label-amount').css('display', 'none');
                    $('#btn_submit').attr('disabled',false);
                    $('#amountLoan').val(0);

                    $('#day1').val(24);
                    $('#day').val(24);

                    $('#porcentage1').val(20);
                    $('#porcentage').val(20);

                    $('#amountPorcentage1').val(0);
                    $('#amountPorcentage').val(0);

                    $('#amountDay1').val(0);
                    $('#amountDay').val(0);

                    $('#amountTotal1').val(0);
                    $('#amountTotal').val(0);
                    
                    $('#day1').attr('disabled',true);
                    $('#porcentage1').attr('disabled',true);
                    $('#amountPorcentage1').attr('disabled',true);
                }
                if(type=='diarioespecial')
                {
                    $('#label-amount').css('display', 'none');
                    $('#btn_submit').attr('disabled',false);
                    $('#amountLoan').val(0);

                 
                    $('#day1').val(0); //0
                    $('#day').val(0);



                    $('#amountDay1').val(0);
                    $('#amountDay').val(0);

                    $('#amountTotal1').val(0);
                    $('#amountTotal').val(0);

                    $('#porcentage1').val(0);//0
                    $('#porcentage').val(0);

                    $('#amountPorcentage1').val(0);
                    $('#amountPorcentage').val(0);

                    $('#day1').attr('disabled',false);         
                    $('#porcentage1').attr('disabled',false);     
                    $('#amountPorcentage1').attr('disabled',false);

                    
                }
            }
            function diasPagar()
            {
                let day = $(`#day1`).val() ? parseFloat($(`#day1`).val()) : 0;
                $('#day').val(day);

                subTotal()
            }
            function porcentagePagar()
            {
                let porcentage = $(`#porcentage1`).val() ? parseFloat($(`#porcentage1`).val()) : 0;
                $('#porcentage').val(porcentage);

                let amountLoan = $(`#amountLoan`).val() ? parseFloat($(`#amountLoan`).val()) : 0;

                porcentage = porcentage/100;
                let amountPorcentage = amountLoan*porcentage;
                $(`#amountPorcentage1`).val(amountPorcentage.toFixed(2));
                $(`#amountPorcentage`).val(amountPorcentage.toFixed(2));

                subTotal()
            }
            function porcentageAmount()
            {
                let amountPorcentage = $(`#amountPorcentage1`).val() ? parseFloat($(`#amountPorcentage1`).val()) : 0;
                $('#amountPorcentage').val(amountPorcentage.toFixed(2));

                let amountLoan = $(`#amountLoan`).val() ? parseFloat($(`#amountLoan`).val()) : 0;

                amountPorcentage = amountPorcentage/amountLoan;
                amountPorcentage = amountPorcentage*100;
                
                $(`#porcentage1`).val(amountPorcentage.toFixed(2));
                $(`#porcentage`).val(amountPorcentage.toFixed(2));

                subTotal();

            }
            function subTotal()
            {
                let type = $(".radio-type:checked").val();
                if(type=='diario')
                {
                    $(`#text_type`).val('diario');

                    let amountLoan = $(`#amountLoan`).val() ? parseFloat($(`#amountLoan`).val()) : 0;
                    let porcentage = $(`#porcentage`).val() ? parseFloat($(`#porcentage`).val()) : 0;

                    let day = $(`#day`).val() ? parseFloat($(`#day`).val()) : 0;

                    porcentage = porcentage/100;
                    let amountPorcentage = amountLoan*porcentage;
                    let amountTotal = amountLoan+amountPorcentage;
                    let amountDay = amountTotal / day;

                

                    $(`#amountPorcentage1`).val(amountPorcentage);
                    $(`#amountTotal1`).val(amountTotal);         

                    $(`#amountPorcentage`).val(amountPorcentage);
                    $(`#amountTotal`).val(amountTotal);  

                    $(`#amountDay1`).val(amountDay);
                    $(`#amountDay`).val(amountDay);  

                    if (amountDay % 1 == 0) {
                        $('#label-amount').css('display', 'none');
                        $('#btn_submit').attr('disabled',false);

                    } else {
                        $('#label-amount').css('display', 'block');
                        $('#btn_submit').attr('disabled',true);
                    }
                }
                if(type=='diarioespecial')
                {
                    $(`#text_type`).val('diarioespecial');

                    let amountLoan = $(`#amountLoan`).val() ? parseFloat($(`#amountLoan`).val()) : 0;
                    let day = $(`#day1`).val() ? parseFloat($(`#day1`).val()) : 0;


                    let porcentage = $(`#porcentage1`).val() ? parseFloat($(`#porcentage1`).val()) : 0;
                    $('#porcentage').val(porcentage.toFixed(2));

                    porcentage = porcentage/100;
                    porcentage = amountLoan*porcentage;

                    $(`#amountPorcentage1`).val(porcentage.toFixed(2));
                    $(`#amountPorcentage`).val(porcentage.toFixed(2));


                    let amountPorcentage = $(`#amountPorcentage1`).val() ? parseFloat($(`#amountPorcentage1`).val()) : 0;


                    let amountTotal = (amountLoan+amountPorcentage).toFixed(2);

                    $(`#amountTotal1`).val(amountTotal);         
                    $(`#amountTotal`).val(amountTotal);  

                    let amountDay = amountTotal / day;

                    amountDay = Math.trunc(amountDay);
                    
                    let amountDayTotal =amountDay * day;

                    let aux = amountTotal-amountDayTotal;
                    aux = amountDay+aux;



                    $(`#amountDay1`).val((aux?aux.toFixed(2):0)+' - '+(amountDay!='Infinity'?amountDay.toFixed(2):0));
                    $(`#amountDay`).val(amountDay);  
                    
                }
            }




            $(function()
            {
                $('#people_id').on('change', onselect_guarantor);
            });

            function onselect_guarantor()
            {      
                var people =  $(this).val();

                var guarantor=$("#guarantor_id").val();

                if(people)
                {
                    if(people == guarantor || guarantor == null)
                    {
                        $.get('{{route('loans-ajax.notpeople')}}/'+people, function(data){
                            var html_guarantor=    '<option value="" disabled selected>-- Seleccionar un garante --</option>'
                                for(var i=0; i<data.length; ++i)
                                    html_guarantor += '<option value="'+data[i].id+'">'+data[i].last_name1+' '+data[i].last_name2+' '+data[i].first_name+'</option>'

                            $('#guarantor_id').html(html_guarantor);                           
                            
                        });
                    }
                    else
                    {
                        $.get('{{route('loans-ajax.notpeople')}}/'+people, function(data){
                            var html_people=    '<option value="" disabled selected>-- Seleccionar un garante --</option>'
                                for(var i=0; i<data.length; ++i)
                                    html_people += '<option value="'+data[i].id+'">'+data[i].last_name1+' '+data[i].last_name2+' '+data[i].first_name+'</option>'

                            $('#guarantor_id').html(html_people);                           
                            
                        });
                    }
                    
                }
                else
                {
                    alert(0)
                }
            }


            
            
            // function inputNumeric(event) {
            //     if(event.charCode >= 48 && event.charCode <= 57){
            //         return true;
            //     }
            //     return false;        
            // }


            function filterFloat(evt,input){
                // Backspace = 8, Enter = 13, ‘0′ = 48, ‘9′ = 57, ‘.’ = 46, ‘-’ = 43
                var key = window.Event ? evt.which : evt.keyCode;    
                var chark = String.fromCharCode(key);
                var tempValue = input.value+chark;
                if(key >= 48 && key <= 57){
                    if(filter(tempValue)=== false){
                        return false;
                    }else{       
                        return true;
                    }
                }
                // else{
                //     if(key == 8 || key == 13 || key == 46 || key == 0) {            
                //         return true;              
                //     }else{
                //         return false;
                //     }
                // }
            }
            function filter(__val__){
                var preg = /^([0-9]+\.?[0-9]{0,2})$/; 
                if(preg.test(__val__) === true){
                    return true;
                }else{
                return false;
                }
                
            }

        </script>


        <script>
            $(document).ready(function(){
                var productSelected;
                
                $('#select_people_id').select2({
                // tags: true,
                    placeholder: '<i class="fa fa-search"></i> Buscar...',
                    escapeMarkup : function(markup) {
                        return markup;
                    },
                    language: {
                        inputTooShort: function (data) {
                            return `Por favor ingrese ${data.minimum - data.input.length} o más caracteres`;
                        },
                        noResults: function () {
                            return `<i class="far fa-frown"></i> No hay resultados encontrados`;
                        }
                    },
                    quietMillis: 250,
                    minimumInputLength: 2,
                    ajax: {
                        url: "{{ url('admin/loans/people/ajax') }}",        
                        processResults: function (data) {
                            let results = [];
                            data.map(data =>{
                                results.push({
                                    ...data,
                                    disabled: false
                                });
                            });
                            return {
                                results
                            };
                        },
                        cache: true
                    },
                    templateResult: formatResultCustomers_people,
                    templateSelection: (opt) => {
                        productSelected = opt;
                        // alert(opt)
                        
                        return opt.first_name?opt.first_name+' '+opt.last_name1+' '+opt.last_name2:'<i class="fa fa-search"></i> Buscar... ';
                    }
                }).change(function(){
                
                });

                $('#select_guarantor_id').select2({
                // tags: true,
                    placeholder: '<i class="fa fa-search"></i> Buscar...',
                    escapeMarkup : function(markup) {
                        return markup;
                    },
                    language: {
                        inputTooShort: function (data) {
                            return `Por favor ingrese ${data.minimum - data.input.length} o más caracteres`;
                        },
                        noResults: function () {
                            return `<i class="far fa-frown"></i> No hay resultados encontrados`;
                        }
                    },
                    quietMillis: 250,
                    minimumInputLength: 2,
                    ajax: {
                        url: "{{ url('admin/loans/people/ajax') }}",        
                        processResults: function (data) {
                            let results = [];
                            data.map(data =>{
                                results.push({
                                    ...data,
                                    disabled: false
                                });
                            });
                            return {
                                results
                            };
                        },
                        cache: true
                    },
                    templateResult: formatResultCustomers_people,
                    templateSelection: (opt) => {
                        productSelected = opt;
                        // alert(opt)
                        
                        return opt.first_name?opt.first_name+' '+opt.last_name1+' '+opt.last_name2:'<i class="fa fa-search"></i> Buscar... ';
                    }
                }).change(function(){
                
                });


                // $('#form-create-customer').submit(function(e){
                //     e.preventDefault();
                //     $('.btn-save-customer').attr('disabled', true);
                //     $('.btn-save-customer').val('Guardando...');
                //     $.post($(this).attr('action'), $(this).serialize(), function(data){
                //         if(data.people.id){
                //             toastr.success('Registrado exitosamente', 'Éxito');
                //             $(this).trigger('reset');
                //         }else{
                //             toastr.error(data.error, 'Error');
                //         }
                //     })
                //     .always(function(){
                //         $('.btn-save-customer').attr('disabled', false);
                //         $('.btn-save-customer').text('Guardar');
                //         $('#modal-create-customer').modal('hide');
                //     });
                // });

            })

            function formatResultCustomers_people(option){
            // Si está cargando mostrar texto de carga
                if (option.loading) {
                    return '<span class="text-center"><i class="fas fa-spinner fa-spin"></i> Buscando...</span>';
                }
                let image = "{{ asset('images/default.jpg') }}";
                if(option.image){
                    image = "{{ asset('storage') }}/"+option.image.replace('.', '-cropped.');
                    // alert(image)
                }
                
                // Mostrar las opciones encontradas
                return $(`  <div style="display: flex">
                                <div style="margin: 0px 10px">
                                    <img src="${image}" width="50px" />
                                </div>
                                <div>
                                    <small>CI: </small><b style="font-size: 15px; color: black">${option.ci?option.ci:'No definido'}</b><br>
                                    <b style="font-size: 15px; color: black">${option.first_name} ${option.last_name1} ${option.last_name2} </b>
                                </div>
                            </div>`);
            }

        </script>
    @stop

@endif