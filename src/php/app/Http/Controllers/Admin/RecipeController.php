<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Repositories\Traits\ErrorTrait;
use App\Repositories\RecipeRepository;

class RecipeController extends Controller
{
    use ErrorTrait;

    protected $recipeRepository;

    /**
     * Constructor
     * @param RecipeRepository $recipeRepository
     */
    public function __construct(RecipeRepository $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }

    /**
     * Get list of recipes
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

            $page_url = '/recipe?page=' . $params['page'] . '&limit=' . $params['limit'] . '&search_by_keywords=' . urlencode($params['search_by_keywords']);
            Session::put('page.recipes', url($page_url));

            if ($request->ajax()) {
                $response = $this->getRecipes($params);
                return $response;
            }

            $params['page_title'] = 'Recipes';
            $params['page_name']  = 'recipes';

            return view('admin.recipe.index', $params);
        } catch (\Exception $e) {
            return Redirect::to($this->getNotFoundPageURL());
        }
    }

    /**
     * @param $recipe_uid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function view($recipe_uid)
    {
        try {
            $recipe = $this->recipeRepository->getRecipeByUid($recipe_uid, $user_id = '', $is_admin = true);

            return view('admin.recipe.view',[
                'page_title' => 'View Recipe',
                'page_name'  => 'view_recipe',
                'recipe'       => $recipe
            ]);
        } catch (\Exception $e) {
            $error = $this->getErrorMessage($e->getMessage());
            return Redirect::to('recipe')->withInput()->with('flash_message', [
                'status'       => 'fail',
                'code'         => $e->getCode(),
                'message'      => $error['message'],
                'error_fields' => $error['error']
            ]);
        }
    }

    /**
     * @param $recipe_uid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function reject($recipe_uid)
    {
        try {
            $this->recipeRepository->rejectRecipe($recipe_uid);

            return Redirect::to('recipe')->with('flash_message', [
                'status'  => 'success',
                'message' => 'Recipe rejected successfully'
            ]);
        } catch (\Exception $e) {
            $error = $this->getErrorMessage($e->getMessage());
            return Redirect::to('recipe')->withInput()->with('flash_message', [
                'status'       => 'fail',
                'code'         => $e->getCode(),
                'message'      => $error['message'],
                'error_fields' => $error['error']
            ]);
        }
    }

    /**
     * @param $recipe_uid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function approve($recipe_uid)
    {
        try {
            $this->recipeRepository->approveRecipe($recipe_uid);

            return Redirect::to('recipe')->with('flash_message', [
                'status'  => 'success',
                'message' => 'Recipe approved successfully'
            ]);
        } catch (\Exception $e) {
            $error = $this->getErrorMessage($e->getMessage());
            return Redirect::to('recipe')->withInput()->with('flash_message', [
                'status'       => 'fail',
                'code'         => $e->getCode(),
                'message'      => $error['message'],
                'error_fields' => $error['error']
            ]);
        }
    }

    /**
     * Ajax: Get list of recipes
     * @param $params
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View | \Illuminate\Http\JsonResponse
     */
    public function getRecipes($params)
    {
        try {
            $recipes = $this->recipeRepository->getRecipesByAdmin($params);

            if (!empty($recipes['data'])) {

                //Generate paginator using Laravel paginator class
                $paginator            = new LengthAwarePaginator($recipes['data'], $recipes['total'], $recipes['per_page'],
                    $params['page'], ['path' => '/recipe']);
                $paginator            = $paginator->appends($params);
                $recipes['paginator'] = $paginator;
            }
            return view('admin.recipe.list', $recipes);
        } catch (\Exception $e) {
            return Response::json(['error' => true]);
        }
    }
}
