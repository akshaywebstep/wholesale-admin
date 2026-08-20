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
    public function priceForUser($user = null)
    {
        if (!$user) return $this->base_price;

        $tier = $this->priceTiers()
            ->where(function ($q) use ($user) {
                $q->where('customer_group_id', $user->customer_group_id)
                    ->orWhereNull('customer_group_id');
            })
            ->where('min_qty', '<=', 1)
            ->orderByDesc('min_qty')
            ->first();

        return $tier->price ?? $this->base_price;
    }
}