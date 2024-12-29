<?php
/**
 * Class AuthRepository
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use App\Exceptions\AuthenticationException;
use App\Exceptions\UserException;
use App\Models\User;
use App\Models\PasswordReset;
use App\Notifications\ActivateAccount;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;

class AuthRepository
{
    /**
     * Registers a User
     *
     * @param array $params
     * @param string $role
     * @param bool $trigger_notification
     * @return User
     * @throws AuthenticationException
     */
    public function create($params, $role = NULL, $trigger_notification = true)
    {
        try {
            $user = User::where('email', $params['email'])->first();
            if ($user) {
                throw new AuthenticationException("The email has already been taken");
            }

            $user = new User([
                'uid' => (string) Str::uuid(),
                'name' => $params['name'],
                'email' => $params['email'],
                'password' => Hash::make($params['password']),
                'activation_token' => (string) Str::random(60),
                'role' => !empty($role) ? $role : config('constants.roles')['user']
            ]);
            $user->save();

        } catch (AuthenticationException $e) {
            Log::debug(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'AuthenticationException thrown AuthRepository@create', [
                'exception_type' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line_no' => $e->getLine(),
                'params' => func_get_args()
            ]);
            throw $e;

        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown AuthRepository@create', [
                'exception_type' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line_no' => $e->getLine(),
                'params' => func_get_args()
            ]);

            throw new AuthenticationException($e->getMessage(), $e->getCode());
        }

        try {
            if ($trigger_notification) {
                $user->notify(new ActivateAccount());
            }
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown AuthRepository@create', [
                'exception_type' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line_no' => $e->getLine(),
                'params' => func_get_args()
            ]);
        }

        return $user;
    }

    /**
     * @param $params
     * @return array
     * @throws AuthenticationException
     */
    public function login($params)
    {
        try {
            $user = User::where('email',  $params['email'])->first();
            if (!$user || !Hash::check($params['password'], $user->password)) 
            {
                throw new AuthenticationException("An email password combination might be incorrect");
            }

            if (empty($user->email_verified_at)) {
                throw new AuthenticationException("Your account has not been activated. Please check your email to activate the account");
            }

            if ($user->status != config('constants.user_statuses')['approved']) {
                switch ($user->status) {
                    case config('constants.user_statuses')['pending']:
                        throw new AuthenticationException("Your account has not been approved yet.");
                        break;
                    case config('constants.user_statuses')['disapproved']:
                        throw new AuthenticationException("Your account has been disapproved.");
                        break;
                    case config('constants.user_statuses')['suspended']:
                        throw new AuthenticationException("Your account has been suspended.");
                        break;
                }
            }

            $user->tokens()->delete();

            return [
                'user' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken
            ];
        } catch (AuthenticationException $e) {
            Log::debug(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'AuthenticationException thrown AuthRepository@login', [
                'exception_type' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line_no' => $e->getLine(),
                'params' => func_get_args()
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown AuthRepository@login', [
                'exception_type' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line_no' => $e->getLine(),
                'params' => func_get_args()
            ]);
            throw new AuthenticationException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param $params
     * @return array
     * @throws AuthenticationException
     */
    public function activateAccount($params)
    {
        try {
            $user = User::where('email', $params['email'])->where('activation_token', $params['token'])->first();
            if (!$user) {
                throw new UserException("Activation code is invalid.");
            }

            $user->email_verified_at = Carbon::now();
            $user->activation_token = null;
            $user->status = config('constants.user_statuses')['approved'];
            $user->save();

            return $user;
        } catch (UserException $e) {
            Log::debug(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'UserException thrown AuthRepository@activateAccount', [
                'exception_type' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line_no' => $e->getLine(),
                'params' => func_get_args()
            ]);
            throw new AuthenticationException($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown AuthRepository@activateAccount', [
                'exception_type' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line_no' => $e->getLine(),
                'params' => func_get_args()
            ]);
            throw new AuthenticationException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param $params
     * @return bool
     * @throws AuthenticationException
     */
    public function passwordReminder($params)
    {
        try {
            $user = User::where('email', $params['email'])->first();
            if (empty($user)) {
                throw new UserException('Account does not exist');
            }

            $passwordReset = PasswordReset::updateOrCreate(
                ['email' => $params['email']],
                [
                    'email' => $params['email'],
                    'token' => (string) Str::random(60)
                ]
            );

        } catch (UserException $e) {
            Log::debug(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'UserException thrown AuthRepository@passwordReminder', [
                'exception_type' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line_no' => $e->getLine(),
                'params' => func_get_args()
            ]);
            throw new AuthenticationException($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown AuthRepository@passwordReminder', [
                'exception_type' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line_no' => $e->getLine(),
                'params' => func_get_args()
            ]);
            throw new AuthenticationException($e->getMessage(), $e->getCode());
        }

        try {
            if ($passwordReset) {
                $user->notify(new PasswordResetRequest($passwordReset->token));
            }
        } catch (\Exception $e) {
            // Don't log if any error from mailgun
        }

        return true;
    }

    /**
     * @param $params
     * @return bool
     * @throws AuthenticationException
     */
    public function passwordReset($params)
    {
        try {
            $passwordReset = PasswordReset::where([
                ['token', $params['token']],
                ['email', $params['email']]
            ])->first();
            if (empty($passwordReset)) {
                throw new AuthenticationException('Expired or invalid activation code');
            }

            $user = User::where('email', $params['email'])->first();
            if (empty($user)) {
                throw new UserException('User not found');
            }

            $user->password = Hash::make($params['password']);
            $user->save();

            $passwordReset->delete();

        } catch (AuthenticationException $e) {
            Log::debug(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'AuthenticationException thrown AuthRepository@passwordReset', [
                'exception_type' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line_no' => $e->getLine(),
                'params' => func_get_args()
            ]);
            throw $e;
        } catch (UserException $e) {
            Log::debug(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'UserException thrown AuthRepository@passwordReset', [
                'exception_type' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line_no' => $e->getLine(),
                'params' => func_get_args()
            ]);
            throw new AuthenticationException($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown AuthRepository@passwordReset', [
                'exception_type' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line_no' => $e->getLine(),
                'params' => func_get_args()
            ]);
            throw new AuthenticationException($e->getMessage(), $e->getCode());
        }

        $user->tokens()->delete();

        try {
            $user->notify(new PasswordResetSuccess(false));
        } catch (\Exception $e) {
            // Don't log if any error from mailgun
        }

        return true;
    }

    /**
     * Change password
     *
     * @param array $params
     * @param $user
     * @return void
     * @throws \Exception
     */
    public function passwordChange($params, $user)
    {
        try {
            $params['email'] = $user->email;

            $user = User::where('email',  $params['email'])->first();
            if (!$user || !Hash::check($params['password'], $user->password)) 
            {
                throw new AuthenticationException("Your current password might be incorrect");
            }

            $user->password = Hash::make($params['new_password']);
            $user->save();
        } catch (AuthenticationException $e) {
            Log::debug(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'AuthenticationException thrown AuthRepository@passwordChange', [
                'exception_type' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line_no' => $e->getLine(),
                'params' => func_get_args()
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown AuthRepository@passwordChange', [
                'exception_type' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line_no' => $e->getLine(),
                'params' => func_get_args()
            ]);
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param $user
     * @return mixed
     * @throws AuthenticationException
     */
    public function refreshToken($user)
    {
        try {
            $user->currentAccessToken()->delete();

            return [
                "token" => $user->createToken('auth_token')->plainTextToken
            ];

        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown AuthRepository@refreshToken', [
                'exception_type' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line_no' => $e->getLine(),
                'params' => func_get_args()
            ]);
            throw new AuthenticationException($e->getMessage(), $e->getCode());
        }
    }
}
