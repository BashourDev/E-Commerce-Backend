<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Specific;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

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
        return response($orders->orderByDesc('updated_at')->paginate(15, ['*'], 'page', $request->get('page')));
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
            'currency' => $request->get('currency'),
            'discount' => $request->get('discount'),
            'address' => auth()->user()->address,
            'phone' => $request->user()->phone,
            'status' => ORDER::STATUS_PENDING
        ]);

        foreach ($request->get('soldItems') as $soldItem) {
            $order->soldItems()->create([
                'specific_id' => $soldItem['specific_id'],
                'sellPrice' => $soldItem['sellPrice'],
                'quantity' => $soldItem['quantity']
            ]);
            Specific::query()->find($soldItem['specific_id'])->decrement('quantity', $soldItem['quantity']);
        }

        auth()->user()->cart->specifics()->detach();

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
        $this->authorize('view', [$order]);

        return response($order->loadMissing(['user', 'soldItems', 'soldItems.specific']));
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

    public function userOrders()
    {
        return \response(auth()->user()->loadMissing(['orders' => function ($query) {
            $query->orderByDesc('updated_at');
        }])->loadCount(['soldItems']));
    }

}
