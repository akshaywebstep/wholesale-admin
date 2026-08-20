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
        'unit_id',
        'weight',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
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

    public function getTotalStockAttribute(): int
    {
        return (int) ($this->variants ? $this->variants->flatMap->stocks->sum('quantity') : 0);
    }

    public function getFormattedWeightAttribute(): string
    {
        if ($this->weight === null) return '';
        $num = rtrim(rtrim(number_format((float)$this->weight, 3, '.', ''), '0'), '.');
        $unit = $this->unit ? ($this->unit->short_code ?? $this->unit->name) : '';
        return $unit ? "{$num} {$unit}" : $num;
    }

    public function priceForUser($user = null, $quantity = 1)
    {
        $tier = $this->priceTiers()
            ->where('min_qty', '<=', $quantity)
            ->where(function ($q) use ($quantity) {
                $q->whereNull('max_qty')
                  ->orWhere('max_qty', '>=', $quantity);
            })
            ->orderByDesc('min_qty')
            ->first();

        return $tier ? (float) $tier->price : (float) $this->base_price;
    }
}
