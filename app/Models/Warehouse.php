<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouses';

    protected $fillable = [
        'name',
        'code',
        'location',
        'status',
        'manager_name',
        'contact_phone',
        'contact_email',
        'tax_number',
        'operating_hours',
        'dispatch_notes',
    ];

    // Relationship with Stock
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function getTotalStockUnitsAttribute(): int
    {
        return (int) $this->stocks->sum('quantity');
    }

    public function getTotalValuationAttribute(): float
    {
        return (float) $this->stocks->reduce(function ($total, $stock) {
            $price = $stock->productVariant && $stock->productVariant->product ? $stock->productVariant->product->base_price : 0;
            return $total + ($stock->quantity * $price);
        }, 0);
    }

    public function getLowStockCountAttribute(): int
    {
        return (int) $this->stocks->filter(function ($stock) {
            return $stock->quantity > 0 && $stock->quantity <= $stock->threshold;
        })->count();
    }

    public function getOutOfStockCountAttribute(): int
    {
        return (int) $this->stocks->filter(function ($stock) {
            return $stock->quantity <= 0;
        })->count();
    }
}