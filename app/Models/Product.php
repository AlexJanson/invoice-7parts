<?php

namespace App\Models;

use App\Casts\IsoDate;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    const PAGINATE_AMOUNT = 50;

    protected $appends = [
        'price_incl_tax'
    ];

    protected $casts = [
        'created_at' => IsoDate::class
    ];

    protected $guarded = [];

    public function priceInclTax(): Attribute
    {
        return Attribute::get(function () {
            return ($this->price / 100) * ($this->tax + 100);
        });
    }
}
