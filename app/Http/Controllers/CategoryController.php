<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(Category::query()->where('type', '=', Category::TYPE_ROOT)->with(['sections', 'sections.items'])->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = new Category();
        $category->name = $request->get('name');
        $category->type = $request->get('type')['id'];
        if ($request->get('type')['id'] !== Category::TYPE_ROOT) {
            $category->parent_id = $request->get('parent')['id'];
        }
        $category->save();
        return response($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        return response($category->delete());
    }

    public function rootCategories()
    {
        return response(Category::query()->where('type', '=', Category::TYPE_ROOT)->get());
    }

    public function sectionCategories()
    {
        return response(Category::query()->where('type', '=', Category::TYPE_SECTION)->get());
    }

    public function itemCategories()
    {
//        $tree = Category::withRecursiveQueryConstraint(function (\Staudenmeir\LaravelAdjacencyList\Eloquent\Builder $query) {
//            $query->where('categories.type', Category::TYPE_SECTION);
//        }, function () {
//            return Category::tree()->get();
//        });

        return response(Category::tree()->whereDepth(2)->get());
    }
}
