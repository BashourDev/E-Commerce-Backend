<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use function Symfony\Component\HttpFoundation\toArray;
use function Symfony\Component\String\length;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::query()->with(['brand', 'categories'])->where('name', 'like', '%'.$request->get('search').'%')->paginate(8, ['*'], 'page', $request->get('page'));
        $updatedProducts = $products->transform(function ($item, $key) {
            foreach ($item->getMedia() as $media) {
                $media['link'] = $media->getFullUrl();
            }
            return $item; });
        return response($products->setCollection($updatedProducts));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = Product::query()->create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'buyPrice' => $request->get('buyPrice'),
            'sellPrice' => $request->get('sellPrice'),
            'discount' => $request->get('discount'),
            'brand_id' => json_decode($request->get('brand'))->id
        ]);
//        return response(json_decode($request->get('specifics')));

        foreach (json_decode($request->get('specifics')) as $specific) {
            $product->specifics()->create([
                'color' => $specific->color,
                'size' => $specific->size,
                'quantity' => $specific->quantity
            ]);
//            return $specific;
        }

        $photos_names = array();

        for ($i = 0; $i < $request->get('photosCount'); $i++) {
            $photos_names[$i] = 'photo'.$i;
        }

        $product
            ->addMultipleMediaFromRequest($photos_names)
            ->each(function ($fileAdder) {
                $fileAdder->toMediaCollection();
            });

        $tags = array();

        $tagsCount = 0;
        foreach (json_decode($request->get('tags')) as $tag) {
            $tags[$tagsCount] = $tag->id;
        }

        $product->tags()->sync($tags);

        $product->categories()->sync(json_decode($request->get('category'))->id);

        return response($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return response($product->loadMissing('specifics'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $product = $product->update([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'buyPrice' => $request->get('buyPrice'),
            'sellPrice' => $request->get('sellPrice'),
            'discount' => $request->get('discount')
        ]);

        return response($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        return response($product->delete());
    }

    public function updateSpecifics(Request $request, Product $product)
    {
        $product->specifics()->updateOrCreate(['color', 'size', 'quantity'], $request->get('specifics'));
        return response('updated', 201);
    }

    public function addImages(Request $request, Product $product)
    {
//        foreach ((array) $request->get('photos') as $photo) {
//            $product->addMedia($photo);
//        }
//        return response($request->file('photos'));

//        $product->addMedia($request->file('photos'))->toMediaCollection();


//        return $request->allFiles();
//
//        foreach($photos as $image) {
//            $product->addMedia($image)->toMediaCollection();
//            return response('ok from media');
//        }

//        $photos = (array) $request->get('photos');

        $photos_names = array();

        for ($i = 0; $i < $request->get('photosCount'); $i++) {
            $photos_names[$i] = 'photo'.$i;
        }

        $product
            ->addMultipleMediaFromRequest($photos_names)
            ->each(function ($fileAdder) {
                $fileAdder->toMediaCollection();
            });

//        $product->addMediaFromRequest('photos')->toMediaCollection();
        return response('ok');
    }
}
