<?php
/**
 * Admin Trait
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Repositories\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

trait AdminTrait
{
    /**
     * Save admin details after login
     * @param $params
     * @return boolean
     */
    public function saveAdmin($params)
    {
        try {
            Session::put('admin_logged_in_user', $params);

            return true;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown AdminTrait@saveAdmin', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            return false;
        }

    }

    /**
     * Get logged-in admin details
     * @param $params
     * @return boolean
     */
    public function getLoggedInAdmin()
    {
        try {
            $session_name  = 'admin_logged_in_user';
            $check_session = Session::get($session_name);
            if (empty($check_session)) {
                return false;
            }

            return Session::get($session_name);
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown AdminTrait@getLoggedInAdmin', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            return false;
        }
    }

    /**
     * Checks if admin is logged in
     * @return boolean
     */
    public static function isAdminLoggedIn()
    {
        try {
            $check_session = Session::get('admin_logged_in_user');
            if (empty($check_session)) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown AdminTrait@isAdminLoggedIn', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            return false;
        }
    }
}
