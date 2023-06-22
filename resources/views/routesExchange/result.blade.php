<form id="form-exchange" action="{{route('routes-loan-exchange.transfer')}}" method="POST" enctype="multipart/form-data">
@csrf
<div class="col-md-12 text-right">
    <a type="button" data-toggle="modal" data-target="#modal_solicituds" title="Enviar solicitud de trabajo" class="btn btn-success"><i class="fa-solid fa-file-export"></i> <span class="hidden-xs hidden-sm"> Transferir</span></a>
</div>

<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="table-responsive">
                <table id="dataStyle" style="width:100%"  class="table dataTable table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th colspan="2" >Codigo</th>
                            <th>Fecha Solicitud</th>
                            <th>Fecha Entrega</th>
                            <th>Nombre Cliente</th>                    
                            <th>Tipo de Préstamos</th>                    
                            <th>Monto Prestado</th>                    
                            <th>Interés a Cobrar</th>   
                            <th>Total a Pagar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @forelse ($data as $item)
                        <tr>
                            <td>
                                <label>
                                    <input type="checkbox" name="loans[]" id="{{ 'check-'.$i}}" value="{{$item->id}}" onchange="toggleCheckbox(this, {{$item->id}})">
                                </label>
                            </td>
                            <td><small>{{ $item->code }}</small></td>
                            <td>{{ date("d-m-Y", strtotime($item->date)) }}</td>
                            <td>{{ date("d-m-Y", strtotime($item->dateDelivered)) }}</td>
                            <td>
                                <table>                                                    
                                    @php
                                        $image = asset('images/icono-anonimato.png');
                                        if($item->people->image){
                                            $image = asset('storage/'.str_replace('.', '-cropped.', $item->people->image));
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <img src="{{ $image }}" alt="{{strtoupper($item->people->first_name)}} {{strtoupper($item->people->last_name1)}} {{strtoupper($item->people->last_name2)}}" style="width: 60px; height: 60px; border-radius: 30px; margin-right: 10px">
                                        </td>
                                        <td>
                                            <small>CI:</small> {{ $item->people->ci }} <br>
                                            <small>NOMBRE:</small> {{strtoupper($item->people->first_name)}} {{strtoupper($item->people->last_name1)}} {{strtoupper($item->people->last_name2)}}
                                        </td>
                                    </tr>
                                    
                                </table>
                            </td>
                            <td>
                                @if ($item->typeLoan == 'diario')
                                    Diario
                                @endif
                                @if ($item->typeLoan == 'diarioespecial')
                                    Diario Especial
                                @endif
                            </td>
                            <td style="text-align: right"> <small>Bs.</small> {{$item->amountLoan}}</td>
                            <td style="text-align: right"> <small>Bs.</small> {{$item->amountPorcentage}}</td>
                            <td style="text-align: right"> <small>Bs.</small> {{$item->amountTotal}}</td>
                            
                        </tr>
                        @empty
                            
                        @endforelse
                        
                    </tbody>
                </table>

                <input type="hidden" name="loanss" id="input_loan">
                <input type="hidden" name="count" id="input_count">

            {{-- $('#input_loan').val(arr[]); --}}

            </div>
        </div>
    </div>
</div>

<div class="modal modal-primary" id="modal_solicituds" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
                 
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa-solid fa-route"></i> Cambio de Ruta</h4>
            </div>
            <div class="modal-body">

                <div class="text-center" style="text-transform:uppercase">
                    <i class="fa-solid fa-route" style="color: rgb(87, 87, 87); font-size: 5em;"></i>
                    <br>
                    <p><b>Desea Cambiar de Ruta...!</b></p>
                </div>
                <div class="row">   
                    <div class="col-md-12">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><b>Rutas:</b></span>
                        </div>
                        <select name="route_id" id="persons" class="form-control select2" required>
                            <option value="" disabled selected>--Seleccione una opcion--</option>
                            @foreach ($route as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>                
                </div>

                {{-- <div class="row">   
                    <div class="col-md-12">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><b>Observacion:</b></span>
                        </div>
                        <textarea id="detail" class="form-control" name="detail" cols="77" rows="3"></textarea>
                    </div>                
                </div> --}}
            </div>       
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <input type="submit" class="btn btn-dark" value="Sí, cambiar">

            </div>
        </div>
    </div>
</div>
</form> 


<style>
    .select2{
        width: 100% !important;
    }
</style>
<script>
    $(function(){
        // alert(2)
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
        var arr = [];
        var count =0;

        function toggleCheckbox(element,id)
        {
            // alert(id)
            var ok= true;
    
            for( var i = 0; i < arr.length; i++){             
                if ( arr[i] == id) { 
                    arr.splice(i, 1); 
                    ok= false;
                    count--;
                }
            }

            if(arr.length === 0 || ok)
            {   
                arr.push(id);
                count++;
            }

            console.log(JSON.stringify(arr));
            
            $('#input_loan').val(JSON.stringify(arr));
            $('#input_count').val(count);

        }

</script>