<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Jobs\SendSubscriptionJob;
use App\Models\Order;


class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'address' => 'required|string',
            'basket' => 'required|array',
            'basket.*.name' => 'required|string',
            'basket.*.type' => 'required|string',
            'basket.*.price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $order = Order::create($request->only(['first_name', 'last_name', 'address']));

        foreach ($request->basket as $item) {
          $order->basket()->create($item);
          if ($item['type'] === 'subscription') {
              SendSubscriptionJob::dispatch($item);
          }
        }

        return response()->json(['message' => 'Order saved successfully'], 201);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      return "Hello World";
    }
}
