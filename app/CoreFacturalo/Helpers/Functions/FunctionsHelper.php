<?php

    namespace App\CoreFacturalo\Helpers\Functions;


    use Carbon\Carbon;
    use Illuminate\Database\Eloquent\Builder;

    class FunctionsHelper
    {

        /**
         * Establece el periodo como estandar.
         * Se evalua el periodo, para ajustar los valores de las fechas en varios modulos
         *
         * @param $array   array de request
         * @param $d_start string fecha de inicio definida. Se ajustara el valor
         * @param $d_end   string fecha de fin definida. Se ajustara el valor
         */
        public static function setDateInPeriod(&$array, &$d_start, &$d_end)
        {
            $month_start = $array['month_start'];
            $month_end = $array['month_end'];
            $period = $array['period'];
            $date_start = $array['date_start'];
            $date_end = $array['date_end'];

            switch ($period) {
                case 'month':
                    $d_start = Carbon::parse($month_start . '-01')->format('Y-m-d');
                    $d_end = Carbon::parse($month_start . '-01')->endOfMonth()->format('Y-m-d');
                    break;
                case 'between_months':
                    $d_start = Carbon::parse($month_start . '-01')->format('Y-m-d');
                    $d_end = Carbon::parse($month_end . '-01')->endOfMonth()->format('Y-m-d');
                    break;
                case 'date':
                    $d_start = $date_start;
                    $d_end = $date_start;
                    break;
                case 'between_dates':
                    $d_start = $date_start;
                    $d_end = $date_end;
                    break;
            }
        }

        /**
         * Devuelve la sentencia sql formateada
         *
         * @param Builder $query
         *
         * @return string
         */
        public static function getSql(Builder $query)
        {
            $sql = '';

            $data = $query->getBindings();
            $cleanSql = explode('?', $query->toSql());
            foreach ($cleanSql as $index => $sq) {
                $sql .= $sq;
                if (isset($data[$index])) {
                    $sql .= " '" . $data[$index] . "' ";
                }
            }
            return $sql;

        }
    }
