<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataStyle" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th style="text-align: center">Nombre</th>                    
                    <th style="text-align: center">Email</th>               
                    <th style="text-align: center">Rol</th>               
                    <th style="text-align: center">Estado</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>
                        <small>Nombre: </small>{{ $item->name }}
                        <br>
                        <small>CI: </small>{{$item->ci}}
                    </td>
                    <td>{{ $item->email}}</td>
                    <td>{{ $item->role->display_name}}</td>
                    <td style="text-align: center">
                        @if ($item->status)
                            <label class="label label-success">Activo</label>
                        @else
                            <label class="label label-warning">Inactivo</label>
                        @endif
                    </td>
                    <td class="no-sort no-click bread-actions text-right">
                        
                        @if (auth()->user()->hasPermission('status_user'))
                            @if ($item->status == 0)
                                <button title="habilitar" class="btn btn-sm btn-success delete" onclick="habilitarItem('{{ route('user.habilitar', ['user' => $item->id]) }}')" data-toggle="modal" data-target="#habilitar-modal">
                                    <i class="fa-solid fa-thumbs-up"></i> <span class="hidden-xs hidden-sm">Habilitar</span>
                                </button>
                            @endif
                            @if ($item->status == 1)
                                <button title="inabilitar" class="btn btn-sm btn-warning delete" onclick="inhabilitarItem('{{ route('user.inhabilitar', ['user' => $item->id]) }}')" data-toggle="modal" data-target="#inhabilitar-modal">
                                    <i class="fa-solid fa-thumbs-down"></i> <span class="hidden-xs hidden-sm">Desabilitar</span>
                                </button>
                            @endif
                        @endif
                        @if (auth()->user()->hasPermission('edit_user') && $item->status == 1)
                            <a href="{{ route('user.edit', ['user' => $item->id]) }}" title="Editar" class="btn btn-sm btn-primary edit">
                                <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                            </a>
                        @endif
                        
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