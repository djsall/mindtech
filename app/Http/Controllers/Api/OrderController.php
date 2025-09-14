<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderController extends Controller
{
    public function index(Restaurant $restaurant): JsonResource
    {
        $orders = $restaurant->orders;

        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request): JsonResource|JsonResponse
    {
        $data = $request->validated();

        $restaurant = Restaurant::findOrFail($data['restaurantId']);

        $item_ids = data_get($data, 'items.*.id');
        $valid_item_ids = $restaurant->menuItems()->whereKey($item_ids)->pluck('id')->toArray();

        $difference = array_diff($item_ids, $valid_item_ids);

        if (filled($difference)) {

            return response()->json([
                'message' => 'Invalid menu items provided',
            ], 400);
        }

        $order = $restaurant->orders()->create([
            'restaurant_id' => $data['restaurantId'],
            'user_id' => $data['customerId'],
            'items' => $data['items'],
        ]);

        return OrderResource::make($order->fresh());
    }

    public function show(Restaurant $restaurant, Order $order): JsonResource
    {
        return OrderResource::make($order);
    }

    public function update(UpdateOrderRequest $request, Restaurant $restaurant, Order $order): JsonResource
    {
        $data = $request->validated();

        $order->update($data);

        return OrderResource::make($order);
    }
}
