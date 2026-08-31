<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'parent_id', 'image', 'status'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Parent + all children ka total product count
    public function getTotalProductsCountAttribute()
    {
        $childIds = $this->children()->pluck('id');
        $allIds = $childIds->push($this->id);

        return Product::whereIn('category_id', $allIds)
            ->where('is_active', true)
            ->count();
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(storage_path('app/public/' . $this->image))) {
            return asset('storage/' . $this->image);
        }
        return asset('images/product1.png');
    }
}
