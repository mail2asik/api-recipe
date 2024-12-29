<?php
/**
 * Class AuthController
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;

use App\Http\Requests\AuthRegisterRequest;
use App\Http\Requests\AuthLoginRequest;

use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $authRepository;

    /**
     * AuthController constructor.
     * @param AuthRepository $authRepository
     */
    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * @param AuthRegisterRequest $request
     * @return Response
     */
    public function register(AuthRegisterRequest $request)
    {
        try {
            $params = $request->all();
            $result = $this->authRepository->create($params);

            return Response::api($result, 'Registered');
        } catch (\Exception $e) {
            return Response::error('Failed to register user', $e->getMessage(), $e->getCode(), 400);
        }
    }

    /**
     * @param AuthLoginRequest $request
     * @return Response
     */
    public function login(AuthLoginRequest $request)
    {
        try {
            $params = $request->all();
            $result = $this->authRepository->login($params);

            return Response::api($result, 'Logged In');
        } catch (\Exception $e) {
            return Response::error('Failed to login user', $e->getMessage(), $e->getCode(), 400);
        }
    }

    /**
     * @param $email
     * @param $token
     * @return mixed
     */
    public function activateByUrl($email, $token)
    {
        try {
            $result = $this->authRepository->activateAccount([
                "email" => $email,
                "token" => $token
            ]);

            return Response::api($result, 'Activated');
        } catch (\Exception $e) {
            return Response::error('Failed to activate account', $e->getMessage(), $e->getCode(), 400);
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return Response::api(true, 'Logged Out');
        } catch (\Exception $e) {
            return Response::error('Failed to logout user', $e->getMessage(), $e->getCode(), 400);
        }
    }
}
