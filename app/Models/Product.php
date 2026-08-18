<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'description',
        'category_id',
        'base_price',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function priceTiers()
    {
        return $this->hasMany(ProductPriceTier::class);
    }
    
    public function stocks()
    {
        return $this->hasManyThrough(Stock::class, ProductVariant::class);
    }
}