<nav class="navbar navbar-default navbar-fixed-top navbar-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button class="hamburger btn-link">
                <span class="hamburger-inner"></span>
            </button>
            @section('breadcrumbs')
            <ol class="breadcrumb hidden-xs" style="display: none">
                @php
                $segments = array_filter(explode('/', str_replace(route('voyager.dashboard'), '', Request::url())));
                $url = route('voyager.dashboard');
                @endphp
                @if(count($segments) == 0)
                    <li class="active"><i class="voyager-boat"></i> {{ __('voyager::generic.dashboard') }}</li>
                @else
                    <li class="active">
                        <a href="{{ route('voyager.dashboard')}}"><i class="voyager-boat"></i> {{ __('voyager::generic.dashboard') }}</a>
                    </li>
                    @foreach ($segments as $segment)
                        @php
                        $url .= '/'.$segment;
                        @endphp
                        @if ($loop->last)
                            <li>{{ ucfirst(urldecode($segment)) }}</li>
                        @else
                            <li>
                                <a href="{{ $url }}">{{ ucfirst(urldecode($segment)) }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            </ol>
            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('gerente') || auth()->user()->hasRole('cajero'))
                <a href="#" data-toggle="modal" data-target="#notificar-modal1" title="Notificar a todos los deudores" class="btn btn-sm">
                    <i class="fa-brands fa-square-whatsapp" style="color: #43d180; font-size: 35px;"></i> <small></small>
                </a>
            @endif
            @show
        </div>
        <ul class="nav navbar-nav @if (__('voyager::generic.is_rtl') == 'true') navbar-left @else navbar-right @endif">
            <li class="dropdown profile">
                <a href="#" class="dropdown-toggle text-right" data-toggle="dropdown" role="button"
                   aria-expanded="false"><img src="{{ $user_avatar }}" class="profile-img"> <span
                            class="caret"></span></a>
                <ul class="dropdown-menu dropdown-menu-animated">
                    <li class="profile-img">
                        <img src="{{ $user_avatar }}" class="profile-img">
                        <div class="profile-body">
                            <h5>{{ Auth::user()->name }}</h5>
                            <h6>{{ Auth::user()->email }}</h6>
                        </div>
                    </li>
                    <li class="divider"></li>
                    <?php $nav_items = config('voyager.dashboard.navbar_items'); ?>
                    @if(is_array($nav_items) && !empty($nav_items))
                    @foreach($nav_items as $name => $item)
                    <li {!! isset($item['classes']) && !empty($item['classes']) ? 'class="'.$item['classes'].'"' : '' !!}>
                        @if(isset($item['route']) && $item['route'] == 'voyager.logout')
                        
                        <form action="{{ route('voyager.logout') }}" method="POST">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-block">
                                @if(isset($item['icon_class']) && !empty($item['icon_class']))
                                <i class="{!! $item['icon_class'] !!}"></i>
                                @endif
                                {{__($name)}}
                            </button>
                        </form>
                        @else
                        <a href="{{ isset($item['route']) && Route::has($item['route']) ? route($item['route']) : (isset($item['route']) ? $item['route'] : '#') }}" {!! isset($item['target_blank']) && $item['target_blank'] ? 'target="_blank"' : '' !!}>
                            @if(isset($item['icon_class']) && !empty($item['icon_class']))
                            <i class="{!! $item['icon_class'] !!}"></i>
                            @endif
                            {{__($name)}}
                        </a>
                        @endif
                    </li>
                    @endforeach
                    @endif
                </ul>
            </li>
        </ul>
    </div>
</nav>

<div class="modal modal-primary fade" data-backdrop="static" tabindex="-1" id="notificar-modal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa-brands fa-square-whatsapp"></i> Notificar</h4>
            </div>
            <div class="modal-body">
                    {{-- <input type="hidden" id="id"> --}}
                    <input type="hidden" id="phone">
                    <input type="hidden" id="name">
            </div>   
            
            <div class="modal-footer">
                <div class="text-center" style="text-transform:uppercase">
                    <i class="fa-brands fa-square-whatsapp" style="color: #52ce5f; font-size: 5em;"></i>
                    <br>
                    <p><b>Desea notificar a todos los deudores?</b></p>
                </div>
                <input type="submit" class="btn btn-dark pull-right delete-confirm"  onclick="miFunc()" value="Sí, Enviar">
                
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<script>
    function miFunc() {
        let timerInterval
        Swal.fire({
            title: 'Notificacion enviada',
            html: '<h2><i class="fa-regular fa-envelope"></i></h2>',
            timer: 5000,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading()
                const b = Swal.getHtmlContainer().querySelector('b')
                timerInterval = setInterval(() => {
                b.textContent = Swal.getTimerLeft()
                }, 50)
            },
            willClose: () => {
                clearInterval(timerInterval)
            }
            }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer) {
                console.log('I was closed by the timer')
            }
        })
        

        // alert(1);
        $.get('{{route('loans-loanDay.notificationLate')}}', function (data) {
                    // alert(2);
        });
        $("#notificar-modal").modal('hide');
    }
</script>
