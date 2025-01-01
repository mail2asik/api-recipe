<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;

use App\Repositories\Traits\AdminTrait;
use App\Repositories\Traits\ErrorTrait;

class CacheController extends Controller
{
    use AdminTrait, ErrorTrait;

    /**
     * Cache page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $response['page_title'] = 'Cache';
        $response['page_name']  = 'cache';

        return view('admin.cache.index', $response);
    }

    /**
     * Clearing cache
     * @param Request $request
     * @return mixed
     */
    public function clear(Request $request)
    {
        try {
            $user = $this->getLoggedInAdmin();

            Artisan::call('cache:clear');

            return Redirect::back()->withInput()->with('flash_message', [
                'status'  => 'success',
                'message' => 'Cache has been cleared successfully'
            ]);
        } catch (\Exception $e) {
            $error = $this->getErrorMessage($e->getMessage());
            return Redirect::back()->withInput()->with('flash_message', [
                'status'        => 'fail',
                'code'          => $e->getCode(),
                'message'       => $error['message'],
                'error_fields'  => $error['error']
            ]);
        }
    }
}
