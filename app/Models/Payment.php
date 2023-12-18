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

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::saved(function (Payment $payment) {
            $payment->invoice->updatePaymentStatus();
        });

        static::updated(function (Payment $payment) {
            $payment->invoice->updatePaymentStatus();
        });

        static::deleted(function (Payment $payment) {
            $payment->invoice->updatePaymentStatus();
        });
    }

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
