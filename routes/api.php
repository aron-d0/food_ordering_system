<?php

use App\Http\Controllers\Api\FoodController;
use Illuminate\Support\Facades\Route;

Route::apiResource('foods', FoodController::class);
