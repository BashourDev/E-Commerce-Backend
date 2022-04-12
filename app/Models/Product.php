<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['name', 'description', 'buyPrice', 'sellPrice', 'discount', 'brand_id'];

    protected $with = ['brand'];

    public function firstMediaOnly()
    {
        return $this->morphOne(config('media-library.media_model'), 'model');
    }

    public function specifics()
    {
        return $this->hasMany(Specific::class, 'product_id', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    public function keywords()
    {
        return $this->hasMany(Keywords::class, 'product_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'products_tags');
    }
}
