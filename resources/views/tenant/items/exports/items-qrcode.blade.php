<!DOCTYPE html>
<html lang="es">
    <head>
    </head>
    <body>
        @if(!empty($record))
            <div class="">
                <div class=" ">
                    <table class="table" width="100%">
                        @php
                            function withoutRounding($number, $total_decimals) {
                                $number = (string)$number;
                                if($number === '') {
                                    $number = '0';
                                }
                                if(strpos($number, '.') === false) {
                                    $number .= '.';
                                }
                                $number_arr = explode('.', $number);

                                $decimals = substr($number_arr[1], 0, $total_decimals);
                                if($decimals === false) {
                                    $decimals = '0';
                                }

                                $return = '';
                                if($total_decimals == 0) {
                                    $return = $number_arr[0];
                                } else {
                                    if(strlen($decimals) < $total_decimals) {
                                        $decimals = str_pad($decimals, $total_decimals, '0', STR_PAD_RIGHT);
                                    }
                                    $return = $number_arr[0] . '.' . $decimals;
                                }
                                return $return;
                            }
                        @endphp
                        {{-- @for($i=1; $i <= $stock; $i++) --}}
                        <tr>
                            @for($j=0; $j < 2; $j++)
                            <td class="celda" width="33%" style="text-align: center; padding-top: 10px; padding-bottom: 10px; font-size: 9px; vertical-align: top;">
                                <p>{{$record->currency_type->symbol}} {{withoutRounding($record->sale_unit_price, 2)}}</p>
                                <p>
                                    @php
                                        $qrCode = QrCode::size(300)->generate($record->barcode);
                                        $bodytag = str_replace('<?xml version="1.0" encoding="UTF-8"?>', "", $qrCode);
                                    @endphp
                                    {!! $bodytag !!}
                                </p>
                                <p>{{$record->barcode}}</p>
                            </td>
                            @endfor
                        </tr>
                        {{-- @endfor --}}
                    </table>
                </div>
            </div>
        @else
            <div>
                <p>No se encontraron registros.</p>
            </div>
        @endif
    </body>
</html>
