<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Repositories\RecipeRepository;

class RecipeSeeder extends Seeder
{
    protected $recipeRepository;

    public function __construct(RecipeRepository $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $recipes = File::getRequire(base_path('database/seeders/data/Recipes.php'));

        foreach ($recipes as $k => $v) {
            $this->createRecipe($v);
        }
    }

    protected function createRecipe($param)
    {
        $recipe = $this->recipeRepository->create($param, $user_id = 1, $request = null);

        // Approve immediately
        $recipe->status = config('constants.recipe_statuses')['approved'];
        $recipe->save();
    }
}
