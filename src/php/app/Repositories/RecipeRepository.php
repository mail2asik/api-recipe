<?php
/**
 * Class RecipeRepository
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Exceptions\RecipeException;
use App\Models\Recipe;
use App\Repositories\Traits\ModelTrait;

class RecipeRepository
{
    use ModelTrait;

    /**
     * @param Recipe $recipe
     */
    public function __construct(Recipe $recipe)
    {
        $this->setModel($recipe);
    }

    /**
     * Get a businesses
     * @param array $params
     * @param $user_id
     * @return array (Recipe)
     * @throws RecipeException
     */
    public function getRecipes($params, $user_id)
    {
        try {
            $recipes = $this->getModel()->select('uid', 'category', 'title', 'created_at', 'updated_at')->where('user_id', $user_id);

            if (!empty($params['search_by_keywords'])) {
                $recipes = $recipes->where('title', 'like', '%'.$params['search_by_keywords'].'%');
            }

            $recipes = $recipes->skip(($params['page'] - 1) * $params['limit'])
                ->take($params['limit'])
                ->get();
            if (count($recipes) === 0) {
                return [];
            }

            return $recipes->toArray();
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown RecipeRepository@getRecipes', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw new RecipeException($e->getMessage(), $e->getCode());
        }
    }

     /**
     * Create a recipe
     * @param $params
     * @param $user_id
     * @return Recipe
     * @throws RecipeException
     */
    public function create($params, $user_id)
    {
        try {
            $recipe = new Recipe();

            $recipe->uid = (string) Str::uuid();
            $recipe->user_id = $user_id;
            $recipe->category = in_array($params['category'], config('constants.recipe_category')) ? $params['category'] : config('constants.recipe_category')['veg'];
            $recipe->title = $params['title'];
            $recipe->slug = Str::slug($params['title']);
            $recipe->image_uid = '';
            $recipe->ingredients = $params['ingredients'];
            $recipe->short_desc = $params['short_desc'];
            $recipe->long_desc = $params['long_desc'];
            $recipe->status = config('constants.recipe_statuses')['pending'];

            $recipe->save();

            return $recipe;
        } catch (RecipeException $e) {
            Log::debug(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'RecipeException thrown RecipeRepository@create', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw $e;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown RecipeRepository@create', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw new RecipeException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Get a recipe by uid
     * @param $recipe_uid
     * @param $user_id
     * @return Recipe
     * @throws RecipeException
     */
    public function getRecipeByUid($recipe_uid, $user_id)
    {
        try {
            $recipe = $this->getModel()->where('user_id', $user_id)->where('uid', $recipe_uid)->first();

            if (empty($recipe)) {
                throw new RecipeException('Recipe not found');
            }

            return $recipe;
        } catch (RecipeException $e) {
            Log::debug(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'RecipeException thrown RecipeRepository@getRecipeByUid', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw $e;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown RecipeRepository@getRecipeByUid', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw new RecipeException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update recipe by uid
     * @param $params
     * @param $recipe_uid
     * @param $user_id
     * @return Recipe
     * @throws RecipeException
     */
    public function updateRecipeByUid($params, $recipe_uid, $user_id)
    {
        try {
            $recipe = $this->getRecipeByUid($recipe_uid, $user_id);
            $recipe->category = $params['category'];
            $recipe->title = $params['title'];
            $recipe->ingredients = $params['ingredients'];
            $recipe->short_desc = $params['short_desc'];
            $recipe->long_desc = $params['long_desc'];

            $recipe->save();

            return $recipe;

        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown RecipeRepository@updateRecipeByUid', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw new RecipeException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Delete recipe by uid
     * @param $params
     * @param $recipe_uid
     * @param $user_id
     * @return Boolean
     * @throws RecipeException
     */
    public function deleteRecipeByUid($recipe_uid, $user_id)
    {
        try {
            $recipe = $this->getRecipeByUid($recipe_uid, $user_id);

            $recipe->delete();

            return true;

        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown RecipeRepository@deleteRecipeByUid', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw new RecipeException($e->getMessage(), $e->getCode());
        }
    }
}
