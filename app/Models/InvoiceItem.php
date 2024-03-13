<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $appends = [
        'total', 'subtotal'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function subtotal(): Attribute
    {
        return Attribute::get(function () {
            return $this->price * $this->quantity / 100;
        });
    }

    public function total(): Attribute
    {
        return Attribute::get(function () {
            return round(($this->subtotal * (1 - ($this->discount / 100))) * (($this->tax + 100) / 100));
        });
    }
}
