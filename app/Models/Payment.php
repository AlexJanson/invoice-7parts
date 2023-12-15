<?php

namespace App\Models;

use App\Casts\IsoDate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    const PAGINATE_AMOUNT = 8;

    const STATUS_UNPAID = 'UNPAID';
    const STATUS_PARTIALLY_PAID = 'PARTIALLY_PAID';
    const STATUS_PAID = 'PAID';

    protected $guarded = [];

    protected $casts = [
        'payment_date' => IsoDate::class
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
