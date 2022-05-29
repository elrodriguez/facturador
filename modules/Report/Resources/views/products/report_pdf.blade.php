<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Productos ventas entre fechas</title>
    <style>
        html {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-spacing: 0;
            border: 1px solid black;
        }

        .celda {
            text-align: center;
            padding: 5px;
            border: 0.1px solid black;
        }

        th {
            padding: 5px;
            text-align: center;
            border-color: #0088cc;
            border: 0.1px solid black;
        }

        .title {
            font-weight: bold;
            padding: 5px;
            font-size: 20px !important;
            text-decoration: underline;
        }

        p>strong {
            margin-left: 5px;
            font-size: 13px;
        }

        thead {
            font-weight: bold;
            background: #0088cc;
            color: white;
            text-align: center;
        }

    </style>
</head>

<body>
    <div>
        <p align="center" class="title"><strong>Productos ventas entre fechas</strong></p>
    </div>
    <div style="margin-top:20px; margin-bottom:20px;">
        <table>
            <tr>
                <td>
                    <p><strong>Empresa: </strong>{{ $company->name }}</p>
                </td>
                <td>
                    <p><strong>Fecha: </strong>{{ date('Y-m-d') }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>Ruc: </strong>{{ $company->number }}</p>
                </td>

            </tr>
        </table>
    </div>
    @if (!empty($products))
        @foreach ($products as $product)
            <table style="background-color: #BFBBBA">
                <tr>
                    <th align="center" colspan="6">{{ $product['name'] }}</th>
                </tr>
                <tr>
                    <th align="center" colspan="6">VENTAS</th>
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
                            <td align="center">{{ $row['series'] }}</td>
                            <td align="right">{{ $row['number'] }}</td>
                            <td align="right">{{ number_format($row['quantity'], 2, '.', '') }}</td>
                            <td align="right">{{ number_format($row['total'], 2, '.', '') }}</td>
                        </tr>
                        @php
                            $total_unidades = $total_unidades + $row['quantity'];
                            $total_monto = $total_monto + $row['total'];
                        @endphp
                    @endforeach
                    <tr>
                        <td colspan="4" align="right" style="border-top: 1px solid">Totales Venta</td>
                        <td align="right" style="border-top: 1px solid">
                            {{ number_format($total_unidades, 2, '.', '') }}</td>
                        <td align="right" style="border-top: 1px solid">{{ number_format($total_monto, 2, '.', '') }}
                        </td>
                    </tr>
                @else
                    <tr>
                        <th align="center" colspan="6">NO EXISTEN REGISTROS</th>
                    </tr>
                @endif
                <tr>
                    <th align="center" colspan="6">COMPRAS</th>
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
                            <td align="center">{{ $row['series'] }}</td>
                            <td align="right">{{ $row['number'] }}</td>
                            <td align="right">{{ number_format($row['quantity'], 2, '.', '') }}</td>
                            <td align="right">{{ number_format($row['total'], 2, '.', '') }}</td>
                        </tr>
                        @php
                            $ctotal_unidades = $ctotal_unidades + $row['quantity'];
                            $ctotal_monto = $ctotal_monto + $row['total'];
                        @endphp
                    @endforeach
                    <tr>
                        <td colspan="4" align="right" style="border-top: 1px solid">Totales Compras</td>
                        <td align="right" style="border-top: 1px solid">
                            {{ number_format($ctotal_unidades, 2, '.', '') }}</td>
                        <td align="right" style="border-top: 1px solid">
                            {{ number_format($ctotal_monto, 2, '.', '') }}</td>
                    </tr>
                @else
                    <tr>
                        <th align="center" colspan="6">NO EXISTEN REGISTROS</th>
                    </tr>
                @endif
            </table>
            <br>
        @endforeach
    @else
        <div class="callout callout-info">
            <p>No se encontraron registros.</p>
        </div>
    @endif
</body>

</html>
