<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'size',
        'color',
        'variant_sku',
    ];

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'product_variant_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}