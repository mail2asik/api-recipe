<?php
/**
 * Dashboard Trait
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Repositories\Traits;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

trait DashboardTrait
{
    /**
     * @param $data
     * @return array
     */
    public function getCountsByDate($data)
    {
        try {
            $count_by_date = [];
            foreach ($data as $record) {
                $count_by_date[$record->display_date] = $record->count;
            }

            return $count_by_date;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown DashboardTrait@getCountsByDate', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            return [];
        }
    }


    public function getLabelsAndDataForLast30Days($countsByDate = [])
    {
        try {
            $result = [
                "labels" => [],
                "data" => []
            ];

            for($i = 0; $i < 30; $i++) {
                $date = Carbon::now()->subDays(31)->addDays(1 + $i)->toDateString();
                array_push($result['labels'], Carbon::parse($date)->format('d-m-Y'));
                array_push($result['data'], isset($countsByDate[$date]) ? $countsByDate[$date] : 0);
            }

            return $result;

        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown DashboardTrait@getLabelsAndDataForLast30Days', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            return [];
        }
    }
}
