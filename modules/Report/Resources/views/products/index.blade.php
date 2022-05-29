@extends('tenant.layouts.app')

@section('content')
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Consulta de producto</h3>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <form id="formSearchProductSales" action="{{ route('tenant_reports_products_sales_day_search') }}"
                    method="POST">
                    @csrf
                    <div class="row" style="-ms-flex-align: end!important;align-items: flex-end!important;">
                        <div class="col-md-2 mb-3">
                            <label for="date_start">Fecha desde</label>
                            <input type="date" class="form-control" id="date_start" name="date_start"
                                value="{{ old('date_start') }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="date_end">Fecha hasta</label>
                            <input type="date" class="form-control" id="date_end" name="date_end"
                                value="{{ old('date_end') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="product">Producto</label>
                            <input type="text" class="form-control" id="product" name="product"
                                value="{{ old('product') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                            <button onclick="exportPdf()" type="button" class="btn btn-info">PDF</button>
                            <button onclick="exportExcel()" type="button" class="btn btn-warning">Excel</button>
                        </div>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </form>



                @if ($products)
                    @foreach ($products as $product)
                        <table class="table table-bordered" style="background-color: #BFBBBA">
                            <tr>
                                <th class="text-center" colspan="6">{{ $product['name'] }}</th>
                            </tr>
                            <tr>
                                <th class="text-center" colspan="6">VENTAS</th>
                            </tr>
                            <tr>
                                <th class="">#</th>
                                <th class="">Tipo Documento</th>
                                <th class="">Serie</th>
                                <th class="">Número</th>
                                <th class="">Cantidad Vendida</th>
                                <th class="">Monto Venta</th>
                            </tr>
                            @if (count($product['document_sale']) > 0)
                                @php
                                    $total_unidades = 0;
                                    $total_monto = 0;
                                @endphp
                                @foreach ($product['document_sale'] as $k => $row)
                                    <tr>
                                        <td class="">{{ $k + 1 }}</td>
                                        <td class="">{{ $row['description'] }}</td>
                                        <td class="text-center">{{ $row['series'] }}</td>
                                        <td class="text-right">{{ $row['number'] }}</td>
                                        <td class="text-right">{{ number_format($row['quantity'], 2, '.', '') }}</td>
                                        <td class="text-right">{{ number_format($row['total'], 2, '.', '') }}</td>
                                    </tr>
                                    @php
                                        $total_unidades = $total_unidades + $row['quantity'];
                                        $total_monto = $total_monto + $row['total'];
                                    @endphp
                                @endforeach
                                <tr>
                                    <th colspan="4" class="text-right">Totales Venta</th>
                                    <th class="text-right">{{ number_format($total_unidades, 2, '.', '') }}</th>
                                    <th class="text-right">{{ number_format($total_monto, 2, '.', '') }}</th>
                                </tr>
                            @else
                                <tr>
                                    <th class="text-center" colspan="6">NO EXISTEN REGISTROS</th>
                                </tr>
                            @endif
                            <tr>
                                <th class="text-center" colspan="6">COMPRAS</th>
                            </tr>
                            <tr>
                                <th class="">#</th>
                                <th class="">Tipo Documento</th>
                                <th class="">Serie</th>
                                <th class="">Número</th>
                                <th class="">Cantidad Compra</th>
                                <th class="">Monto Compra</th>
                            </tr>
                            @if (count($product['document_purchase']) > 0)
                                @php
                                    $ctotal_unidades = 0;
                                    $ctotal_monto = 0;
                                @endphp
                                @foreach ($product['document_purchase'] as $k => $row)
                                    <tr>
                                        <td class="">{{ $k + 1 }}</td>
                                        <td class="">{{ $row['description'] }}</td>
                                        <td class="text-center">{{ $row['series'] }}</td>
                                        <td class="text-right">{{ $row['number'] }}</td>
                                        <td class="text-right">{{ number_format($row['quantity'], 2, '.', '') }}</td>
                                        <td class="text-right">{{ number_format($row['total'], 2, '.', '') }}</td>
                                    </tr>
                                    @php
                                        $ctotal_unidades = $ctotal_unidades + $row['quantity'];
                                        $ctotal_monto = $ctotal_monto + $row['total'];
                                    @endphp
                                @endforeach
                                <tr>
                                    <th colspan="4" class="text-right">Totales Compras</th>
                                    <th class="text-right">{{ number_format($ctotal_unidades, 2, '.', '') }}</th>
                                    <th class="text-right">{{ number_format($ctotal_monto, 2, '.', '') }}</th>
                                </tr>
                            @else
                                <tr>
                                    <th class="text-center" colspan="6">NO EXISTEN REGISTROS</th>
                                </tr>
                            @endif
                        </table>
                    @endforeach
                @endif


            </div>
        </div>
    </div>
    <script>
        function exportPdf() {
            let no = $("#product").val();
            let fs = $("#date_start").val();
            let fe = $("#date_end").val();
            let rute = "sales-products_export/pdf/" + no + "/" + fs + "/" + fe;
            window.open(rute, '_blank');
        }

        function exportExcel() {
            let no = $("#product").val();
            let fs = $("#date_start").val();
            let fe = $("#date_end").val();
            let rute = "sales-products_export/excel/" + no + "/" + fs + "/" + fe;
            window.open(rute, '_blank');
        }
    </script>
@endsection
