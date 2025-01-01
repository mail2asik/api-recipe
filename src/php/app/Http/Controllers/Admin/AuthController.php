<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

use App\Repositories\AuthRepository;
use App\Repositories\UserRepository;
use App\Repositories\Traits\AdminTrait;
use App\Repositories\Traits\ErrorTrait;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\AdminChangePasswordRequest;

class AuthController extends Controller
{
    use AdminTrait, ErrorTrait;

    protected $authRepository;

    protected $userRepository;

    /**
     * Constructor
     * @param AuthRepository    $authRepository
     * @param UserRepository    $userRepository
     */
    public function __construct(AuthRepository $authRepository, UserRepository $userRepository)
    {
        $this->authRepository     = $authRepository;
        $this->userRepository     = $userRepository;
    }

    /**
     * Login page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login(Request $request)
    {
        return view('admin.auth.login',[
            'page_title' => config('app.name'). ' - Login',
            'page_name' => 'login'
        ]);
    }

    /**
     *  Submit a web login
     * @param AdminLoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function doLogin(AdminLoginRequest $request)
    {
        try {
            $result = $this->authRepository->login($request->except('_url'), $is_admin = true);

            $this->saveAdmin($result['user']);

            return $this->redirectToIntendedPage($request);

        } catch (\Exception $e) {
            $error = $this->getErrorMessage($e->getMessage());
            return Redirect::to('login')->withInput()->with('flash_message', [
                'status'        => 'fail',
                'code'          => $e->getCode(),
                'message'       => $error['message'],
                'error_fields'  => $error['error']
            ]);
        }
    }

    /**
     * Logout
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        try {
            Session::flush();
            return Redirect::to('/');
        } catch (\Exception $e) {
            return Response::error('Failed to authenticate', $e->getMessage(), $e->getCode(), 401);
        }
    }

    /**
     * Password change page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changePassword(Request $request)
    {
        return view('admin.auth.change_password',[
            'page_title' => 'Change Password',
            'page_name'  => 'change_password'
        ]);
    }

    /**
     * Submit change password request
     * @param AdminChangePasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function doChangePassword(AdminChangePasswordRequest $request)
    {
        try {
            $user = $this->getLoggedInAdmin();

            $this->authRepository->passwordChange($request->except('_url'), $user);

            return Redirect::to('change-password')->withInput()->with('flash_message', [
                'status'  => 'success',
                'message' => 'Password updated successfully'
            ]);

        } catch (\Exception $e) {
            $error = $this->getErrorMessage($e->getMessage());
            return Redirect::to('change-password')->withInput()->with('flash_message', [
                'status'  => 'fail',
                'code'  => $e->getCode(),
                'message' => $error['message'],
                'error_fields' => $error['error']
            ]);
        }
    }

    /**
     * Admin profile page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getProfile(Request $request)
    {
        $admin = $this->getLoggedInAdmin();

        return view('admin.auth.profile',[
            'page_title' => 'Profile',
            'page_name'  => 'profile',
            'user'       => $admin
        ]);
    }

    /**
     * Submit profile update request
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postProfile(Request $request)
    {
        try {
            $user = $this->getLoggedInAdmin();

            $result = $this->authRepository->adminProfileUpdate($request->except('_url'), $user);

            $this->saveAdmin($result);

            return Redirect::to('profile')->withInput()->with('flash_message', [
                'status'  => 'success',
                'message' => 'Profile updated successfully'
            ]);

        } catch (\Exception $e) {
            $error = $this->getErrorMessage($e->getMessage());
            return Redirect::to('profile')->withInput()->with('flash_message', [
                'status'  => 'fail',
                'code'  => $e->getCode(),
                'message' => $error['message'],
                'error_fields' => $error['error']
            ]);
        }
    }

    /**
     * Redirect user to intended page or Dashboard page if there is no intend in session
     * @param $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToIntendedPage($request)
    {
        if (!$request->session()->has('admin_intended_url')) {
            return Redirect::to('dashboard');
        }

        //Redirect to intended url
        $intended_url = substr($request->session()->get('admin_intended_url'), strlen(url('')));

        //Remove _url fromm params
        $intended_url = explode('?', $intended_url);
        if (isset($intended_url[1])) {
            $get_params = $intended_url[1];
            parse_str($get_params, $get_params_arr);

            if (array_key_exists('_url', $get_params_arr)) {
                unset($get_params_arr['_url']);
            }
        }

        // Concatenate the url
        if (isset($get_params_arr) && count($get_params_arr)) {
            $url_redirect = $intended_url[0] . '?' . http_build_query($get_params_arr);
        } else {
            $url_redirect = $intended_url[0];
        }

        $request->session()->forget('admin_intended_url');

        return Redirect::to($url_redirect);
    }
}
