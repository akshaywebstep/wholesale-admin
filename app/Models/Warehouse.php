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
        'location',
        'status',
    ];

    // Relationship with Stock
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}