<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
public function priceTiers()
{
    return $this->hasMany(ProductPriceTier::class);
}
}
