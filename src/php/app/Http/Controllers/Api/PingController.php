<?php
/**
 * Class PingController
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;

class PingController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $response = [
            'request_params' => $request->all(),
            'time' => time()
        ];
        return Response::api($response, 'Ping Updated');
    }
}
