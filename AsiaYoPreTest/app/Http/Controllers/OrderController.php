<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderCheckAndConvertRequest;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{

    public function checkAndConvertOrder(OrderCheckAndConvertRequest $request): JsonResponse
    {
        $validated = $request->validated();

        return response()->json([
            'id' => $validated['id'],
            'name' => $validated['name'],
            'address' => $validated['address'],
            'price' => $validated['price'],
            'currency' => $validated['currency'],
        ], 200);
    }
}
