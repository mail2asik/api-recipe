<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Repositories\AuthRepository;

class UserSeeder extends Seeder
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = File::getRequire(base_path('database/seeders/data/UserCredentials.php'));

        foreach ($users as $k => $v) {
            $this->createUser($v);
        }
    }

    protected function createUser($param)
    {
        $user = $this->authRepository->create($param, $role = $param['role'], $trigger_notification = false);

        // Activate and Approve account immediately
        $user->activation_token = NULL;
        $user->email_verified_at = Carbon::now();
        $user->status = config('constants.user_statuses')['approved'];
        $user->save();
    }
}
