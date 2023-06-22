
<div class="col-md-12 text-right">

    {{-- <button type="button" onclick="report_excel()" class="btn btn-success"><i class="fa-solid fa-file-excel"></i> Excel</button> --}}
    <button type="button" onclick="report_print()" class="btn btn-dark"><i class="glyphicon glyphicon-print"></i> Imprimir</button>

</div>
<div class="col-md-12">
<div class="panel panel-bordered">
    <div class="panel-body">
        <div class="table-responsive">
            <table id="dataStyle" style="width:100%"  class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th rowspan="2" style="width:5px">N&deg;</th>
                        <th rowspan="2" style="text-align: center">CODIGO</th>
                        <th rowspan="2" style="text-align: center">FECHA SOLICITUD</th>
                        <th rowspan="2" style="text-align: center">FECHA ENTREGA</th>
                        <th rowspan="2" style="text-align: center">CI</th>
                        <th rowspan="2" style="text-align: center">CLIENTE</th>
                        <th rowspan="2" style="text-align: center">CELULAR</th>
                        <th rowspan="2" style="text-align: center">DIRECCION</th>
                        <th rowspan="2" style="text-align: center">PAGO DIARIO</th>
                        <th rowspan="2" style="text-align: center">TOTAL DIAS A PAGAR</th>
                        <th rowspan="2" style="text-align: center">MONTO PRESTADO</th>
                        <th rowspan="2" style="text-align: center">INTERES A PAGAR</th>
                        <th rowspan="2" style="text-align: center">TOTAL A PAGAR</th>
                        <th colspan="2" style="text-align: center">RETRAZO</th>
                    </tr>
                    <tr>
                        <th style="text-align: center">DIAS</th>
                        <th style="text-align: center">TOTAL A PAGAR</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $count = 1;
                    @endphp
                    @forelse ($data as $item)
                        <tr style="text-align: center">
                            <td>{{ $count }}</td>
                            <td style="text-align: center">{{ $item->code}}</td>
                            <td style="text-align: center">{{date('d/m/Y', strtotime($item->date))}}</td>
                            <td style="text-align: center">{{date('d/m/Y', strtotime($item->dateDelivered))}}</td>
                            <td style="text-align: center">{{ $item->ci }}</td>
                            <td style="text-align: left">{{ $item->last_name1}} {{ $item->last_name2}} {{ $item->first_name}}</td>
                            <td style="text-align: center">{{ $item->cell_phone}}</td>
                            <td style="text-align: left">{{ $item->street}} <br>
                                {{ $item->home}} <br>
                                {{ $item->zone}}
                            </td>
                            <td style="text-align: right"><small>{{ number_format($item->amountTotal/$item->day,2) }}</small></td>
                            <td style="text-align: right">{{ $item->day }}</td>
                            <td style="text-align: right">{{ number_format($item->amountLoan,2) }}</td>
                            <td style="text-align: right">{{ number_format($item->amountPorcentage,2) }}</td>
                            <td style="text-align: right">{{ number_format($item->amountTotal,2) }}</td>
                            <td style="text-align: right; background-color: #ff7979">{{ $item->diasAtrasado }}</td>
                            <td style="text-align: right; background-color: #ff7979">{{ number_format($item->montoAtrasado,2) }}</td>      
                        </tr>
                        @php
                            $count++;
                            
                        @endphp
                        
                    @empty
                        <tr style="text-align: center">
                            <td colspan="15">No se encontraron registros.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<script>
$(document).ready(function(){

})
</script>