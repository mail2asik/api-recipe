<?php
/**
 * Error Trait
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Repositories\Traits;

use Illuminate\Support\Facades\Log;

trait ErrorTrait
{
    /**
     * Prepare redirect to "not found" page url
     * @return string
     */
    public function getNotFoundPageURL()
    {
        return 'not-found';
    }

    /**
     * Get general error message (or) field error message
     * @param string $message
     * @return array
     */
    public function getErrorMessage($message)
    {
        try {
            $return_message = ["message" => "", "error" => ""];
            if (($jsonObj = json_decode($message, true)) && json_last_error() == JSON_ERROR_NONE) {
                $return_message['message']  = '';
                $return_message['error']    = $jsonObj;

                return $return_message;
            }

            $return_message['message'] = $message;

            return $return_message;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown ErrorTrait@errorMessage', [
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
