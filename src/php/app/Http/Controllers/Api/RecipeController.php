<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use App\Repositories\RecipeRepository;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;

class RecipeController extends Controller
{
    protected $recipeRepository;

    /**
     * RecipeController constructor.
     * @param RecipeRepository $user
     */
    public function __construct(RecipeRepository $recipe)
    {
        $this->recipeRepository = $recipe;
    }

    /**
     * Display a listing of the recipe.
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        try {
            $params['search_by_keywords'] = rawurldecode($request->get('search_by_keywords', ''));
            $params['page']               = $request->get('page', 1);
            $params['limit']              = $request->get('limit', 10);

            $allowed_params = [5, 10, 25, 50, 100];
            if (!in_array($params['limit'], $allowed_params)) {
                $params['limit'] = 25;
            }

            if ($params['page'] != 1 && !filter_var($params['page'], FILTER_VALIDATE_INT)) {
                $params['page'] = 1;
            }

            $data = $this->recipeRepository->getRecipes($params, $request->user()->id);

            $results = [
                'per_page'      => $params['limit'],
                'current_page'  => $params['page'],
                'data'          => $data,
                'next_page_url' => false
            ];

            if (count($data) == $params['limit']) {
                $params['page'] += 1;
                $results['next_page_url'] = $request->url() . '?' . http_build_query($params);
            }

            return Response::api($results, 'Listed');
        } catch (\Exception $e) {
            return Response::error('Failed to list recipes', $e->getMessage(), $e->getCode(), 400);
        }
    }

    /**
     * Store a newly created recipe in storage.
     * @param StoreRecipeRequest $request
     * @return mixed
     */
    public function store(StoreRecipeRequest $request)
    {
        try {
            $result = $this->recipeRepository->create($request->except('image'), $request->user()->id, $request);
            return Response::api($result, 'Created');
        } catch (\Exception $e) {
            return Response::error('Failed to create recipe', $e->getMessage(), $e->getCode(), 400);
        }
    }

    /**
     * Display the specified recipe.
     * @param Request $request
     * @param $recipe_uid
     * @return mixed
     */
    public function show(Request $request, $recipe_uid)
    {
        try {
            $result = $this->recipeRepository->getRecipeByUid($recipe_uid, $request->user()->id);
            return Response::api($result, 'Displayed');
        } catch (\Exception $e) {
            return Response::error('Failed to show recipe', $e->getMessage(), $e->getCode(), 400);
        }
    }

    /**
     * Update the specified recipe in storage.
     * @param UpdateRecipeRequest $request
     * @param $recipe_uid
     */
    public function update(UpdateRecipeRequest $request, $recipe_uid)
    {
        try {
            $result = $this->recipeRepository->updateRecipeByUid($request->all(), $recipe_uid, $request->user()->id);

            return Response::api($result, 'Updated');
        } catch (\Exception $e) {
            return Response::error('Failed to update recipe', $e->getMessage(), $e->getCode(), 400);
        }
    }

    /**
     * Remove the specified recipe from storage.
     * @param Request $request
     * @param $recipe_uid
     */
    public function destroy(Request $request, $recipe_uid)
    {
        try {
            $result = $this->recipeRepository->deleteRecipeByUid($request->recipe_uid, $request->user()->id);

            return Response::api($result, 'Deleted');
        } catch (\Exception $e) {
            return Response::error('Failed to delete recipe', $e->getMessage(), $e->getCode(), 400);
        }
    }
}
