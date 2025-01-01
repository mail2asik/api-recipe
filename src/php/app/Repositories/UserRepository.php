<?php
/**
 * Class UserRepository
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Exceptions\UserException;
use App\Models\User;
use App\Repositories\Traits\ModelTrait;
use App\Repositories\Traits\DashboardTrait;

class UserRepository
{
    use ModelTrait, DashboardTrait;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->setModel($user);
    }

    /**
     * Get a user by uid
     *
     * @param $user_uid
     * @return User
     * @throws UserException
     */
    public function getUserByUserUid($user_uid)
    {
        try {
            $user = $this->getModel()->where('uid', $user_uid)->first();

            if (empty($user)) {
                throw new UserException('User not found');
            }

            return $user;
        } catch (UserException $e) {
            Log::debug(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'UserException thrown UserRepository@getUserByUserUid', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw $e;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown UserRepository@getUserByUserUid', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw new UserException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param $params
     * @param $user
     * @return User
     * @throws UserException
     */
    public function updateUser($params, $user)
    {
        try {
            $user->name = $params['name'];

            $user->save();

            return $this->getUserByUserUid($user->uid);

        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown UserRepository@updateUser', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw new UserException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Get a list of users for admin dashboard
     * @param array  $params
     * @return array
     * @throws \Exception
     */
    public function getUsers($params)
    {
        try {
            $users = $this->getModel()->where('role', '!=', config('constants.roles')['admin']);

            if (!empty($params['roles'])) {
                $roles_array = explode(',', $params['roles']);
                $users = $users->whereIn('role', $roles_array);
            }

            if (!empty($params['search_by_keywords'])) {
                $users = $users->where(function ($q) use($params) {
                    $q->where('email', $params['search_by_keywords']);
                });
            }

            return $users->orderBy('id', 'desc')->paginate($params['limit'])->toArray();
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown UserRepository@getAllUsers', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Suspend a user for admin dashboard
     *
     * @param $user_uid
     * @return bool
     * @throws UserException
     */
    public function suspendUser($user_uid)
    {
        try {
            $user = $this->getUserByUserUid($user_uid);

            $user->status = config('constants.user_statuses')['suspended'];

            $user->save();

            return true;

        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown UserRepository@suspendUser', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw new UserException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Approve a user for admin dashboard
     *
     * @param $user_uid
     * @return bool
     * @throws UserException
     */
    public function approveUser($user_uid)
    {
        try {
            $user = $this->getUserByUserUid($user_uid);

            $user->status = config('constants.user_statuses')['approved'];

            $user->save();

            return true;

        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown UserRepository@approveUser', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw new UserException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Get total number of users
     *
     * @param $status
     * @return integer
     */
    public function getTotalNumberOfUsers($status = '')
    {
        $users = DB::table('users')
            ->select(DB::raw('COUNT(users.id) as `count`'));

        switch ($status) {
            case config('constants.user_statuses')['approved']:
                $users = $users->where('status', config('constants.user_statuses')['approved']);
                break;
            case config('constants.user_statuses')['pending']:
                $users = $users->where('status', config('constants.user_statuses')['pending']);
                break;
            case config('constants.user_statuses')['suspended']:
                $users = $users->where('status', config('constants.user_statuses')['suspended']);
                break;
        }

        $users = $users->first();

        return $users->count;
    }

    /**
     * Get user count per day for last 30 days
     *
     * @return array
     */
    public function getCountPerDayForLast30Days()
    {
        $start_date = Carbon::now()->subDays(31)->startOfDay()->toDateTimeString();
        $end_date   = Carbon::now()->subDays(1)->endOfDay()->toDateTimeString();
        $status     = config('constants.user_statuses')['approved'];

        $query = <<<QUERY
                SELECT count(`id`) as `count`, 
               date(`created_at`) as `display_date`
        FROM   `users`
        where `created_at` >= '$start_date' and `created_at` <= '$end_date' and `status` = '$status'
        GROUP  BY `display_date`
QUERY;

        $query_results = DB::select(DB::raw($query)->getValue(DB::connection()->getQueryGrammar()));

        $countsByDate = $this->getCountsByDate($query_results);

        return $this->getLabelsAndDataForLast30Days($countsByDate);
    }
}
