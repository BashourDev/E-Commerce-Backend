<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['name', 'description', 'buyPrice', 'sellPrice', 'discount', 'brand_id'];


    public function specifics()
    {
        return $this->hasMany(Specific::class, 'product_id', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
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
