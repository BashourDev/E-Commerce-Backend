<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Specific;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response(auth()->user()->cart->specifics()->with(['product'])->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Specific $specific
     * @return Response
     */
    public function store(Request $request, Specific $specific)
    {
        return response(auth()->user()->cart->specifics()->attach([$specific->id], ['quantity' => $request->get('quantity')]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return Response
     */
    public function destroy(Cart $cart)
    {
        //
    }

    public function deleteSpecific(Specific $specific)
    {
        return \response(auth()->user()->cart->specifics()->detach([$specific->id]));
    }
}
