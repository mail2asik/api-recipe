<?php
/**
 * API Response class
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Http\Response;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Fluent;

/**
 * Standard API Response base class.
 *
 * Implements a base class that API responses can descend from.
 * The response macro 'api' is defined to use this class.
 *
 * ###Example:
 *
 * ```
 *   // Returns a 200 response encoded in JSON
 *   return Response::api($data, $message, $status, $extra);
 * ```
 */
class ApiResponse extends Fluent
{
    /**
     * Constructor
     *
     * @param array|object $data the data to display
     * @param string $message 1-line message to display
     * @param array|object $extra
     * @throws \Exception
     */
    public function __construct($data, $message, $extra = [])
    {
        $user = Request::user();
        if (!empty($user)) {
            $extra = array_merge($extra, [
                'metadata' => [
                    'user_uid' => $user->uid
                ]
            ]);
        }

        $object = array_merge([
            'status'    => 'OK',
            'message'   => $message,
            'response'  => $data
        ], $extra);

        parent::__construct($object);
    }
}
