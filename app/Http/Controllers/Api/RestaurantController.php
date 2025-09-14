<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RestaurantResource;
use App\Models\Restaurant;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantController extends Controller
{
    public function index(): JsonResource
    {
        $restaurants = Restaurant::all();

        return RestaurantResource::collection($restaurants);
    }

    public function show(Restaurant $restaurant): JsonResource
    {
        $restaurant->load('menuItems');

        return RestaurantResource::make($restaurant);
    }
}
