<?php
/**
 * Class UserRepository
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Repositories;

use Illuminate\Support\Facades\Log;

use App\Exceptions\UserException;
use App\Models\User;
use App\Repositories\Traits\ModelTrait;

class UserRepository
{
    use ModelTrait;

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
}
