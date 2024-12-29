<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('uid',36)->after('id')->default('');
            $table->string('activation_token')->after('email_verified_at')->nullable();
            $table->enum('role', ['user', 'admin'])->after('activation_token')->default('user');
            $table->enum('status', ['pending', 'approved', 'disapproved', 'suspended'])->after('role')->default('pending');

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
