@extends('voyager::master')

@section('page_title', 'hola')

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
          
            <div class="col-md-12">
                
                <div class="panel panel-bordered">
                    <div class="row">
                        <br>
                        <div class="col-xs-4 col-sm-4 text-right">
                            
                        </div>
                        <div class="col-xs-4 col-sm-4 text-center">
                           
                        </div>
                        <div class="col-xs-4 col-sm-4">
                            
                        </div>
                        <div class="col-md-6 col-sm-6">
                            
                        </div>
                        <div class="col-md-6 col-sm-6 text-right">
                            
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" style="margin-bottom: 10px">
                        <div class="panel panel-bordered">
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="table-responsive">
                                       
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover" id="table-visitor">
                                            <thead>
                                                <tr>
                                                    <th style="width: 50px">N&deg;</th>
                                                    <th>Nombre</th>
                                                </tr>
                                            </thead>
                                            <input type="text" class="form-control" id="input">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

 
@stop

@section('css')
    <style>
        @font-face {
            font-family: 'Seven Segment';
            src: url({{ asset('fonts/Seven-Segment.ttf') }});
        }
        .td-actions img{
            filter: grayscale(100%);
        }
        .td-actions img:hover{
            filter: grayscale(0%);;
            /* width: 28px */
        }
        .img-avatar{
            width: 30px;
            height: 30px;
            border-radius: 15px;
            margin-right: 5px
        }
        #label-score{
            font-family: 'Seven Segment';
            font-size: 100px
        }
        #timer{
            font-family: 'Seven Segment';
            font-size: 60px;
            color: #E74C3C
        }
    </style>
@endsection

@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.4.0/socket.io.js" integrity="sha512-nYuHvSAhY5lFZ4ixSViOwsEKFvlxHMU2NHts1ILuJgOS6ptUmAGt/0i5czIgMOahKZ6JN84YFDA+mCdky7dD8A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    

    <script>
        const socket = io("{{ env('SOCKET_URL').':'.env('SOCKET_PORT') }}");
        $(document).ready(function () {

            // socket.emit(`reload score`, {id: "Hola"});

            
        });

        input.oninput = function() {
            text = input.value;
            socket.emit(`reload score`, {id: text});

        };
      </script>
@stop
