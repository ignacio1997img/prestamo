@extends('voyager::master')

@section('page_title', 'Crear Prendario')

@if (auth()->user()->hasPermission('add_garments'))

    @section('page_header')
        <h1 id="titleHead" class="page-title">
            <i class="fa-solid fa-handshake"></i> Crear Prendario
        </h1>
        {{-- <a href="{{ route('garments.index') }}" class="btn btn-warning">
            <i class="fa-solid fa-rotate-left"></i> <span>Volver</span>
        </a> --}}
    @stop

    @section('content')
        <div class="page-content edit-add container-fluid">    
            <form id="agent" action="{{route('garments.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-heading">
                                <h5 id="h4" class="panel-title">Detalle del Prendario</h5>
                            </div>
                            <div class="panel-body">
                                @if (!$cashier)  
                                    <div class="alert alert-warning">
                                        <strong>Advertencia:</strong>
                                        <p>No puedes registrar debido a que no tiene una caja asignada.</p>
                                    </div>
                                @else     
                                    @if ($cashier->status != 'abierta')
                                        <div class="alert alert-warning">
                                            <strong>Advertencia:</strong>
                                            <p>No puedes registrar debido a que no tiene una caja activa.</p>
                                        </div>
                                    @endif
                                @endif
                                <h5>Datos Personales</h5>
                                <div class="row">
                                    <div class="form-group col-md-8">
                                        <small for="customer_id">Beneficiario del Prestamo</small>
                                        <select name="people_id" class="form-control" id="select_people_id" required></select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <small for="customer_id">Fotocopia CI/NIT (Fotografía)</small>
                                        <input type="file" accept="image/jpeg,image/jpg,image/png,application/pdf" name="fileCi" id="fileCi" class="form-control text imageLength">
                                    </div>
                                </div>
                                <hr>
                                <h5>Detalle de la Prenda</h5>

                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <small for="customer_id">Artículo</small>
                                        <select name="article_id" class="form-control" id="select_article_id" required></select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <small for="customer_id">Prenda (Fotografía)</small>
                                        <input type="file" accept="image/jpeg,image/jpg,image/png" multiple name="filePrenda[]" id="filePrenda" class="form-control text imageLength">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <small for="customer_id">Documento Respaldo (Documento)</small>
                                        <input type="file" accept="image/jpeg,image/jpg,image/png" multiple name="docPrenda[]" id="docPrenda" class="form-control text imageLength">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <small>Fojas Doc. (Cant)</small>
                                        <input type="number" name="amountLoan" id="amountLoan" style="text-align: right" value="0" min="0" step="1" onkeypress="return filterFloat(event,this);" onchange="subTotal()" onkeyup="subTotal()" class="form-control text" required>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <small for="customer_id">Detalle/Descripcion de la Prenda</small>
                                        <textarea name="articleDescription" id="articleDescription" class="form-control" rows="5" required></textarea>
                                    </div>
                                    
                                </div>

                                <hr>
                                <h5>Detalle del Contrato</h5>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <small>Tipo</small>
                                        <select name="contract" id="contract" class="form-control select2" required>
                                            <option value="" disabled selected>--Selecciona una opción--</option>
                                            <option value="compacto">Minuta de Compacto de Rescate</option>
                                            <option value="contratoprivado">Contrato Privado</option>
                                        </select>
                                    </div>    
                                    <div class="form-group col-md-4">
                                        <small>Cantidad Mes</small>
                                        <select name="contractMes" id="contractMes" class="form-control select2" required>
                                            <option value="" disabled selected>--Selecciona una opción--</option>
                                            <option value="1">1 Mes</option>
                                            <option value="3">3 Mes</option>
                                        </select>
                                    </div>                                 
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-2">
                                        <small>Monto a Prestar (Bs.)</small>
                                        <input type="number" name="amountLoan" id="amountLoan" style="text-align: right" value="0" min="1" step=".01" onkeypress="return filterFloat(event,this);" onchange="subTotal()" onkeyup="subTotal()" class="form-control text" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <small>Precio del Dolar ($)</small>
                                        <input type="number" id="priceDollar" name="priceDollar" value="{{setting('configuracion.dollar')}}" style="text-align: right" disabled class="form-control text" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <small>Monto a Prestar ($)</small>
                                        <input type="number" id="amountLoanDollar" name="amountLoanDollar" style="text-align: right" disabled value="0" class="form-control text">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <small>Interes Prestamos (%)</small>
                                        <input type="number" id="porcentage" name="porcentage" value="{{setting('configuracion.porcentageGarment')}}" style="text-align: right" disabled class="form-control text" required>
                                    </div>    
                                    <div class="form-group col-md-2">
                                        <small>Interes a Pagar (Bs.)</small>
                                        <input type="number" id="amountPorcentage" name="amountPorcentage" value="0" step="any" style="text-align: right" disabled class="form-control text" required>
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

            function subTotal()
            {
                // let type = $(".radio-type:checked").val();      
                // alert(1)     

                let amountLoan = $(`#amountLoan`).val() ? parseFloat($(`#amountLoan`).val()) : 0;
                let priceDollar = $(`#priceDollar`).val() ? parseFloat($(`#priceDollar`).val()) : 0;
                let porcentage = $(`#porcentage`).val() ? parseFloat($(`#porcentage`).val()) : 0;

                porcentage = porcentage/100;
                // priceDollar = priceDollar/100;

                let amountPorcentage = amountLoan*porcentage;
                let amountLoanDollar = amountLoan/priceDollar;

                

                $(`#amountPorcentage`).val(amountPorcentage.toFixed(2));
                $(`#amountLoanDollar`).val(amountLoanDollar.toFixed(2));
                // $(`#amountTotal1`).val(amountTotal);         

                // $(`#amountPorcentage`).val(amountPorcentage);
                // $(`#amountTotal`).val(amountTotal);  

                // $(`#amountDay1`).val(amountDay);
                // $(`#amountDay`).val(amountDay);  

                // if (amountDay % 1 == 0) {
                //     $('#label-amount').css('display', 'none');
                //     $('#btn_submit').attr('disabled',false);

                // } else {
                //     $('#label-amount').css('display', 'block');
                //     $('#btn_submit').attr('disabled',true);
                // }
                
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