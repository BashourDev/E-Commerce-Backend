<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $orders = Order::query()->with(['user']);
        if ($request->get('search')) {
            $orders->where('id', '=', $request->get('search'));
        }
        return response($orders->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $order = auth()->user()->orders()->create([
            'totalPrice' => $request->get('totalPrice'),
            'discount' => $request->get('discount'),
            'address' => auth()->user()->address,
            'phone' => $request->user()->phone,
            'status' => ORDER::STATUS_PENDING
        ]);

        foreach ($request->get('soldItems') as $soldItem) {
            $order->soldItems()->create([
                'sellPrice' => $soldItem->sellPrice,
                'quantity' => $soldItem->quantity
            ]);
        }

        return response($order->loadMissing('soldItems'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return Response
     */
    public function show(Order $order)
    {
        return response($order->loadMissing('soldItems'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return Response
     */
    public function destroy(Order $order)
    {
        return response($order->delete());
    }

    public function updateStatus(Request $request, Order $order)
    {
        $order->status = $request->get('status');
        $order->save();
        return response($order->loadMissing('soldItems'));
    }

}
