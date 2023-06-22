<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Nombre</th>                    
                    <th>Descripción</th>               
                    <th style="text-align: center">Estado</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->agentType->name }}</td>
                    <td>{{ $item->people->first_name }} {{ $item->people->last_name }}</td>
                    <td>{{ $item->observation }}</td>
                    <td style="text-align: center">
                        @if ($item->status)
                            <label class="label label-success">Activo</label>
                        @else
                            <label class="label label-warning">Inactivo</label>
                        @endif
                    </td>
                    <td class="no-sort no-click bread-actions text-right">                      

                        {{-- @if (auth()->user()->hasPermission('read_people')) --}}
                            {{-- <a href="{{ route('voyager.people.show', ['id' => $item->id]) }}" title="Ver" class="btn btn-sm btn-warning view">
                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                            </a> --}}
                        {{-- @endif --}}
                        {{-- @if (auth()->user()->hasPermission('edit_people')) --}}
                            <a href="{{ route('agents.edit', ['agent' => $item->id]) }}" title="Editar" class="btn btn-sm btn-primary edit">
                                <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                            </a>
                        {{-- @endif --}}
                        {{-- @if (auth()->user()->hasPermission('delete_people')) --}}
                            <button title="Borrar" class="btn btn-sm btn-danger delete" onclick="deleteItem('{{ route('agents.destroy', ['agent' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal">
                                <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                            </button>
                        {{-- @endif --}}
                    </td>
                </tr>
                @empty
                    <tr style="text-align: center">
                        <td valign="top" colspan="6" class="dataTables_empty">No hay datos disponibles en la tabla</td>
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