<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\Traits\AdminTrait;
use App\Repositories\UserRepository;
use App\Repositories\RecipeRepository;

class DashboardController extends Controller
{
    use AdminTrait;

    protected $userRepository;
    protected $recipeRepository;

    /**
     * Constructor
     * @param UserRepository $userRepository
     * @param RecipeRepository $recipeRepository
     */
    public function __construct(UserRepository $userRepository, RecipeRepository $recipeRepository)
    {
        $this->userRepository = $userRepository;
        $this->recipeRepository = $recipeRepository;
    }

    /**
     * Dashboard page
     *
     * @param $request
     * @return view
     */
    public function index(Request $request)
    {
        $response['page_title'] = 'Dashboard';
        $response['page_name']  = 'dashboard';

        $response['users'] = [
            'approved' => $this->userRepository->getTotalNumberOfUsers($status = config('constants.user_statuses')['approved']),
            'pending' => $this->userRepository->getTotalNumberOfUsers($status = config('constants.user_statuses')['pending']),
            'suspended' => $this->userRepository->getTotalNumberOfUsers($status = config('constants.user_statuses')['suspended']),
            'users_statistics' => $this->userRepository->getCountPerDayForLast30Days()
        ];

        $response['recipes'] = [
            'approved' => $this->recipeRepository->getTotalNumberOfRecipes($status = config('constants.recipe_statuses')['approved']),
            'pending' => $this->recipeRepository->getTotalNumberOfRecipes($status = config('constants.recipe_statuses')['pending']),
            'rejected' => $this->recipeRepository->getTotalNumberOfRecipes($status = config('constants.recipe_statuses')['rejected'])
        ];

        return view('admin.dashboard.index', $response);
    }
}
