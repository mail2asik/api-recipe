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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();

            $table->string('uid',36)->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->enum('category', ['veg', 'non_veg'])->default('veg');
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->string('image_uid',36)->nullable();
            $table->text('ingredients')->nullable();
            $table->text('short_desc')->nullable();
            $table->text('long_desc')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
