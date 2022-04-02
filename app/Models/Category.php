<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Category extends Model
{
    use HasFactory, HasRecursiveRelationships;

    const TYPE_ROOT = 0;
    const TYPE_SECTION = 1;
    const TYPE_ITEM = 2;

    protected $fillable = ['name', 'type', 'parent_id'];

    public function getCustomPaths()
    {
        return [
            [
                'name' => 'custom_path',
                'column' => 'name',
                'separator' => ' / ',
            ],
        ];
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function sections()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id')->where('type', '=', Category::TYPE_SECTION);
    }

    public function items()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id')->where('type', '=', Category::TYPE_ITEM);
    }
}
