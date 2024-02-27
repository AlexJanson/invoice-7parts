<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    const PAGINATE_AMOUNT = 50;

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address');
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    // public function mainContact()
    // {
    //     return $this->contacts()->one()->where('default', '1');
    // }

    // public function otherContacts()
    // {
    //     return $this->contacts()->where('default', '0');
    // }

    public function dueAmount(): Attribute
    {
        return Attribute::get(function() {
            return $this->invoices->reduce(function ($carry, $item) {
                return $carry + $item->due_amount;
            });
        });
    }
}
