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
        if ($this->weight === null || (float)$this->weight <= 0) {
            return '';
        }

        $w = (float) $this->weight;

        // Check if sales unit is a direct weight/volume measure
        $unitCode = strtolower($this->unit->short_code ?? '');
        if (in_array($unitCode, ['kg', 'g', 'gm', 'mg', 'l', 'ml', 'ton', 'q'])) {
            $num = rtrim(rtrim(number_format($w, 3, '.', ''), '0'), '.');
            return "{$num} {$unitCode}";
        }

        // Smart formatting for packaging units (Pack, Box, Piece, Carton, etc.)
        if ($w < 1) {
            $grams = round($w * 1000, 1);
            $gramsStr = rtrim(rtrim(number_format($grams, 1, '.', ''), '0'), '.');
            $kgStr = rtrim(rtrim(number_format($w, 3, '.', ''), '0'), '.');
            return "{$gramsStr}g ({$kgStr} kg)";
        } elseif ($w >= 20 && $w == floor($w)) {
            // E.g. entered as direct grams like 50, 250, 500
            $kg = $w / 1000;
            return "{$w}g ({$kg} kg)";
        } else {
            $num = rtrim(rtrim(number_format($w, 2, '.', ''), '0'), '.');
            return "{$num} kg";
        }
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

    public function getFeaturedImageUrlAttribute(): string
    {
        $first = $this->images->first();
        if ($first && $first->image_path && (file_exists(storage_path('app/public/' . $first->image_path)) || file_exists(public_path('storage/' . $first->image_path)))) {
            return asset('storage/' . $first->image_path);
        }
        return asset('images/product1.png');
    }
}
