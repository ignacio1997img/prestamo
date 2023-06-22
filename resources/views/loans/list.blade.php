<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataStyle" class="table table-bordered table-hover">
            <thead>
                <tr>
                    @if (auth()->user()->hasRole('admin'))
                        <th>ID</th>
                    @endif
                    <th>Codigo</th>
                    <th>Fecha Solicitud</th>
                    <th>Nombre Cliente</th>                    
                    <th>Tipo de Préstamos</th>                    
                    <th>Monto Prestado</th>                    
                    <th>Interés a Cobrar</th>   
                    <th>Total a Pagar</th>
                    <th>Deuda Pendiente</th>                    
                    {{-- <th style="text-align: center">Estado</th> --}}
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                <tr>
                    @if (auth()->user()->hasRole('admin'))
                        <th>{{$item->id}}</th>
                    @endif
                    <td><small>{{ $item->code }}</small></td>
                    <td>{{ date("d-m-Y", strtotime($item->date)) }}</td>
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
                                    <img src="{{ $image }}" alt="{{strtoupper($item->people->first_name)}} {{strtoupper($item->people->last_name1)}} {{strtoupper($item->people->last_name2)}}" style="width: 50px; height: 50px; border-radius: 30px; margin-right: 10px">
                                </td>
                                <td>
                                    <small>CI:</small> {{ $item->people->ci }} <br>
                                    <small>{{strtoupper($item->people->first_name)}} {{strtoupper($item->people->last_name1)}} {{strtoupper($item->people->last_name2)}} </small>
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
                    {{-- <td style="text-align: right"> <small>Bs.</small> {{$item->debt}}</td> --}}
                    <td style="text-align: right">
                        @if ($item->debt == 0)
                            <label class="label label-success">PAGADO</label>
                        @else
                            <label class="label label-danger"><small>Bs.</small> {{$item->debt}}</label>
                        @endif
                        <br>
                        @if ($item->status == 'pendiente')
                            <label class="label label-danger">PENDIENTE</label>                            
                        @endif
                        @if ($item->status == 'verificado')
                            <label class="label label-warning">VERIFICADO</label>                            
                        @endif
                        @if ($item->status == 'aprobado')
                            <label class="label label-primary">APROBADO</label>                            
                        @endif
                        @if ($item->status == 'entregado')
                            <label class="label label-success">ACTIVO</label>                            
                        @endif
                        @if ($item->status == 'rechazado')
                            <label class="label label-danger">RECHAZADO</label>                            
                        @endif       
                    </td>
                    <td class="no-sort no-click bread-actions text-right">
                        @if ($item->status == 'aprobado')
                            <a href="#" data-toggle="modal" data-target="#notificar-modal" data-name="{{ $item->people->first_name }} {{ $item->people->last_name1 }} {{ $item->people->last_name2 }}" data-phone="{{$item->people->cell_phone}}" title="Notificar al beneficiario" class="btn btn-sm">
                                <i class="fa-brands fa-square-whatsapp" style="color: #43d180; font-size: 35px;"></i>
                            </a>
                            @if (auth()->user()->hasPermission('deliverMoney_loans'))
                                <a title="Entregar dinero al Beneficiario" class="btn btn-sm btn-success" onclick="deliverItem('{{ route('loans-money.deliver', ['loan' => $item->id]) }}',{{$item->id}}, {{$item->amountLoan}})" data-toggle="modal" data-target="#deliver-modal" data-fechass="{{$item->date}}">
                                    <i class="fa-solid fa-money-check-dollar"></i><span class="hidden-xs hidden-sm"> Entregar</span>
                                </a>
                            @endif
                        @endif


                        @if($item->status != 'rechazado')
                            <div class="btn-group" style="margin-right: 3px">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                                    Mas <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu" style="left: -90px !important">
                                    @if ($item->status == 'entregado' && $item->delivered == 'Si')
                                        <li><a href="{{ route('loans-list.transaction', ['loan'=>$item->id])}}" class="btn-transaction"  data-toggle="modal" title="Imprimir Calendario" ><i class="fa-solid fa-money-bill-transfer"></i> Transacciones</a></li> 
                                    @endif
                                    @if ($item->status != 'pendiente' && $item->status != 'verificado' && !auth()->user()->hasRole('cobrador'))
                                        <li><a href="{{ route('loans-print.calendar', ['loan'=>$item->id])}}" class="btn-rotation"  data-toggle="modal" target="_blank" title="Imprimir Calendario" ><i class="fa-solid fa-print"></i> Imprimir Calendario</a></li> 
                                    
                                        <li><a onclick="loan({{$item->id}})" class="btn-rotation"  data-toggle="modal" title="Imprimir Contrato" ><i class="fa-solid fa-print"></i> Imprimir Contrato</a></li>
                                        <li><a onclick="comprobanteDelivered({{$item->id}})" class="btn-rotation"  data-toggle="modal" title="Imprimir Comprobante de Entrega de Prestamos" ><i class="fa-solid fa-print"></i> Imprimir Comprobante Entrega</a></li>
                                    @endif                      
                                </ul>
                            </div>
                        @endif

                            @if(!auth()->user()->hasRole('cobrador') && !auth()->user()->hasRole('cajeros'))
                                <a href="{{ route('loan-routeOld.index', ['loan' => $item->id]) }}" title="Rutas del Prestamo" class="btn btn-sm btn-dark">
                                    <i class="fa-solid fa-route"></i><span class="hidden-xs hidden-sm"></span>
                                </a>
                            @endif

                            @if($item->status != 'rechazado')
                                <a href="{{ route('loans-requirement-daily.create', ['loan' => $item->id]) }}" title="Requisitos" class="btn btn-sm btn-warning">
                                    <i class="fa-solid fa-file"></i><span class="hidden-xs hidden-sm"></span>
                                </a>
                            @endif
                            
                            @if ($item->status=='verificado' && auth()->user()->hasPermission('successLoan_loans') && $item->status != 'rechazado')
                                <a title="Aprobar prestamo" class="btn btn-sm btn-dark" onclick="successItem('{{ route('loans.success', ['loan' => $item->id]) }}')" data-toggle="modal" data-target="#success-modal">
                                    <i class="fa-solid fa-money-check-dollar"></i><span class="hidden-xs hidden-sm"> Aprobar Prestamos</span>
                                </a>
                            @endif


                            @if ($item->status == 'entregado' && $item->status != 'rechazado')
                                <a href="{{ route('loans-daily.money', ['loan' => $item->id, 'cashier_id'=>$cashier_id]) }}" title="Abonar Pago"  class="btn btn-sm btn-success">
                                    <i class="voyager-dollar"></i><span class="hidden-xs hidden-sm"> {{$item->debt == 0?'Ver':'Abonar'}}</span>
                                </a>
                            @endif

                            

                        @if (auth()->user()->hasRole('gerente')||auth()->user()->hasRole('administrador')||auth()->user()->hasRole('admin'))                            
                            @if ($item->status == 'entregado')
                                @php
                                    $destroy = \App\Models\Cashier::where('id', $item->cashier_id)->first();
                                @endphp
                                {{-- @if($destroy->status == 'cerrada') --}}
                                    <button title="Eliminar Prestamo entregado.." class="btn btn-sm btn-danger delete" onclick="destroyItem('{{ route('loans-cashierclose.destroy', ['loan' => $item->id]) }}')" data-toggle="modal" data-target="#destroy-modal">
                                        <i class="fa-solid fa-trash"></i> <span class="hidden-xs hidden-sm"></span>
                                    </button>
                                {{-- @endif --}}
                            @endif
                        @endif
                        @if (auth()->user()->hasPermission('delete_loans'))
                            @if ($item->status != 'rechazado' && $item->status != 'entregado')
                                <button title="Rechazar" class="btn btn-sm btn-dark" onclick="rechazarItem('{{ route('loans.rechazar', ['loan' => $item->id]) }}')" data-toggle="modal" data-target="#rechazar-modal">
                                    <i class="fa-solid fa-thumbs-down"></i> <span class="hidden-xs hidden-sm">Rechazar</span>
                                </button>
                                <button title="Borrar" class="btn btn-sm btn-danger delete" onclick="deleteItem('{{ route('loans.destroy', ['loan' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal">
                                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                                </button>
                            @endif
                        @endif
                    </td>
                    
                    
                </tr>
                @empty
                    <tr>
                        <td style="text-align: center" valign="top" colspan="10" class="dataTables_empty">No hay datos disponibles en la tabla</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="col-md-12">
    <div class="col-md-4" style="overflow-x:auto">
        @if(count($data)>0)
            <p class="text-muted">Mostrando del {{$data->firstItem()}} al {{$data->lastItem()}} de {{$data->total()}} registros.</p>
        @endif
    </div>
    <div class="col-md-8" style="overflow-x:auto">
        <nav class="text-right">
            {{ $data->links() }}
        </nav>
    </div>
</div>

<script>
   
   var page = "{{ request('page') }}";
    $(document).ready(function(){
        $('.page-link').click(function(e){
            e.preventDefault();
            let link = $(this).attr('href');
            if(link){
                page = link.split('=')[1];
                list(page);
            }
        });
    });
</script>