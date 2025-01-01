<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Repositories\Traits\ErrorTrait;
use App\Repositories\UserRepository;

class UserController extends Controller
{
    use ErrorTrait;

    protected $userRepository;

    /**
     * Constructor
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get list of users
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View | \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        try {
            $params['search_by_keywords'] = rawurldecode($request->get('search_by_keywords', ''));
            $params['page']               = $request->get('page', 1);
            $params['limit']              = $request->get('limit', 10);

            $allowed_params = [5, 10, 25, 50, 100];
            if (!in_array($params['limit'], $allowed_params)) {
                $params['limit'] = 10;
            }

            if ($params['page'] != 1 && !filter_var($params['page'], FILTER_VALIDATE_INT)) {
                $params['page'] = 1;
            }

            $page_url = '/user?page=' . $params['page'] . '&limit=' . $params['limit'] . '&search_by_keywords=' . urlencode($params['search_by_keywords']);
            Session::put('page.users', url($page_url));

            if ($request->ajax()) {
                $response = $this->getUsers($params);
                return $response;
            }

            $params['page_title'] = 'Users';
            $params['page_name']  = 'users';

            return view('admin.user.index', $params);
        } catch (\Exception $e) {
            return Redirect::to($this->getNotFoundPageURL());
        }
    }

    /**
     * @param $user_uid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function view($user_uid)
    {
        try {
            $user = $this->userRepository->getUserByUserUid($user_uid);

            return view('admin.user.view',[
                'page_title' => 'View User',
                'page_name'  => 'view_user',
                'user'       => $user
            ]);
        } catch (\Exception $e) {
            $error = $this->getErrorMessage($e->getMessage());
            return Redirect::to('user')->withInput()->with('flash_message', [
                'status'       => 'fail',
                'code'         => $e->getCode(),
                'message'      => $error['message'],
                'error_fields' => $error['error']
            ]);
        }
    }

    /**
     * @param $user_uid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function suspend($user_uid)
    {
        try {
            $this->userRepository->suspendUser($user_uid);

            return Redirect::to('user')->with('flash_message', [
                'status'  => 'success',
                'message' => 'User suspended successfully'
            ]);
        } catch (\Exception $e) {
            $error = $this->getErrorMessage($e->getMessage());
            return Redirect::to('user')->withInput()->with('flash_message', [
                'status'       => 'fail',
                'code'         => $e->getCode(),
                'message'      => $error['message'],
                'error_fields' => $error['error']
            ]);
        }
    }

    /**
     * @param $user_uid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function approve($user_uid)
    {
        try {
            $this->userRepository->approveUser($user_uid);

            return Redirect::to('user')->with('flash_message', [
                'status'  => 'success',
                'message' => 'User approved successfully'
            ]);
        } catch (\Exception $e) {
            $error = $this->getErrorMessage($e->getMessage());
            return Redirect::to('user')->withInput()->with('flash_message', [
                'status'       => 'fail',
                'code'         => $e->getCode(),
                'message'      => $error['message'],
                'error_fields' => $error['error']
            ]);
        }
    }

    /**
     * Ajax: Get list of users
     * @param $params
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View | \Illuminate\Http\JsonResponse
     */
    public function getUsers($params)
    {
        try {
            $params['roles'] = implode(',', array_values(config('constants.roles')));

            $users = $this->userRepository->getUsers($params);

            if (!empty($users['data'])) {

                //Generate paginator using Laravel paginator class
                $paginator          = new LengthAwarePaginator($users['data'], $users['total'], $users['per_page'],
                    $params['page'], ['path' => '/user']);
                $paginator          = $paginator->appends($params);
                $users['paginator'] = $paginator;
            }

            return view('admin.user.list', $users);
        } catch (\Exception $e) {
            return Response::json(['error' => true]);
        }
    }
}
