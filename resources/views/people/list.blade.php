<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataStyle" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>CI</th>
                    <th>Nombre completo</th>                    
                    <th>Fecha nac.</th>
                    <th>Telefono</th>
                    <th>Estado</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->ci }}</td>
                    <td>
                        <table>
                            @php
                                $image = asset('images/icono-anonimato.png');
                                if($item->image){
                                    $image = asset('storage/'.str_replace('.', '-cropped.', $item->image));
                                }
                                $now = \Carbon\Carbon::now();
                                $birthday = new \Carbon\Carbon($item->birth_date);
                                $age = $birthday->diffInYears($now);
                            @endphp
                            <tr>
                                <td><img src="{{ $image }}" alt="{{ $item->first_name }} " style="width: 60px; height: 60px; border-radius: 30px; margin-right: 10px"></td>
                                <td>
                                    {{ strtoupper($item->first_name) }} {{ strtoupper($item->last_name1) }} {{ strtoupper($item->last_name2) }}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>{{ date('d/m/Y', strtotime($item->birth_date)) }} <br> <small>{{ $age }} años</small> </td>
                    <td>{{ $item->cell_phone?$item->cell_phone:'SN' }}</td>
                    <td style="text-align: center">
                        @if ($item->status==1)
                            <label class="label label-success">Activo</label>
                        @endif
                        @if ($item->status==0)
                            <label class="label label-warning">Inactivo</label>
                        @endif
                        <br><br>
                        @if ($item->token==NULL)
                            <label class="label label-danger">Sin Verificar</label>
                        @endif

                        
                    </td>
                    <td class="no-sort no-click bread-actions text-right">
                        @if ($item->token==null)
                            <a href="#" data-toggle="modal" data-target="#verificar-modal" data-name="{{ $item->first_name }} {{ $item->last_name1 }} {{ $item->last_name2 }}" data-id="{{$item->id}}" data-phone="{{$item->cell_phone}}" title="Verificación" class="btn btn-sm btn-success">
                                <i class="fa-solid fa-key"></i> <span class="hidden-xs hidden-sm">Verificar</span>
                            </a>
                        @endif
                        @if (auth()->user()->hasPermission('sponsor_people'))
                            <a href="{{route('people-sponsor.index', ['id'=>$item->id])}}" title="Ver" class="btn btn-sm btn-dark view">
                                <i class="fa-solid fa-medal"></i> <span class="hidden-xs hidden-sm">Patrocinador</span>
                            </a>
                        @endif

                        @if (auth()->user()->hasPermission('read_people'))
                            <a href="{{ route('voyager.people.show', ['id' => $item->id]) }}" title="Ver" class="btn btn-sm btn-warning view">
                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                            </a>
                        @endif
                        @if (auth()->user()->hasPermission('edit_people'))
                            <a href="{{ route('voyager.people.edit', ['id' => $item->id]) }}" title="Editar" class="btn btn-sm btn-primary edit">
                                <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                            </a>
                        @endif
                        {{-- @if (auth()->user()->hasPermission('delete_people'))
                        <button title="Borrar" class="btn btn-sm btn-danger delete" onclick="deleteItem('{{ route('voyager.people.destroy', ['id' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal">
                            <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                        </button>
                        @endif --}}
                    </td>
                </tr>
                @empty
                    <tr style="text-align: center">
                        <td colspan="7" class="dataTables_empty">No hay datos disponibles en la tabla</td>
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