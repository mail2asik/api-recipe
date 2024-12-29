<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;

use App\Repositories\UserRepository;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    protected $userRepository;

    /**
     * UserController constructor.
     * @param UserRepository $user
     */
    public function __construct(UserRepository $user)
    {
        $this->userRepository = $user;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function show(Request $request)
    {
        try {
            return Response::api($request->user(), 'Displayed');
        } catch (\Exception $e) {
            return Response::error('User not exist', $e->getMessage(), $e->getCode(), 400);
        }
    }

    /**
     * @param UserUpdateRequest $request
     * @return mixed
     */
    public function update(UserUpdateRequest $request)
    {
        try {
            $results = $this->userRepository->updateUser($request->all(), $request->user());

            return Response::api($results, 'Updated');
        } catch (\Exception $e) {
            return Response::error('Failed to update user', $e->getMessage(), $e->getCode(), 400);
        }
    }
}
