<?php

namespace App\Http\Controllers;

use App\Models\Specific;
use Illuminate\Http\Request;

class SpecificController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Specific  $specific
     * @return \Illuminate\Http\Response
     */
    public function show(Specific $specific)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Specific  $specific
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Specific $specific)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Specific  $specific
     * @return \Illuminate\Http\Response
     */
    public function destroy(Specific $specific)
    {
        //
    }

    public function searchFilters()
    {
        $colors = Specific::query()->distinct()->get(['colorText']);
        $updatedColors = $colors->transform(function ($item, $key) {
            $item['name'] = $item->colorText;
            return $item; });

        $sizes = Specific::query()->distinct()->get(['size']);
        $updatedSizes = $sizes->transform(function ($item, $key) {
            $item['name'] = $item->size;
            return $item; });

        return response(array(['id' => 1, 'name' => 'Color', 'options' => $updatedColors], ['id' => 2,'name' => 'Size', 'options' => $updatedSizes]));
    }
}
