<?php

namespace App\Models;

use App\Casts\IsoDate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    const PAGINATE_AMOUNT = 50;

    const STATUS_UNPAID = 'UNPAID';
    const STATUS_PARTIALLY_PAID = 'PARTIALLY PAID';
    const STATUS_PAID = 'PAID';

    protected $casts = [
        'invoice_date' => IsoDate::class,
        'due_date' => IsoDate::class
    ];

    protected $appends = [
        'due_amount'
    ];

    protected $guarded = [];

    public function customer() 
    {
        return $this->belongsTo(Customer::class);
    }

    public function contact() 
    {
        return $this->belongsTo(Contact::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function subtotal(): Attribute
    {
        return Attribute::get(function () {
            return $this->items->reduce(function ($carry, $item) {
                return $carry + $item->subtotal * (1 - $item->discount / 100);
            });
        });
    }

    public function tax(): Attribute
    {
        return Attribute::get(function () {
            return $this->total - $this->subtotal;
        });
    }

    public function total(): Attribute
    {
        return Attribute::get(function () {
            return round($this->items->sum('total'));
        });
    }

    public function dueAmount(): Attribute
    {
        return Attribute::get(function () {
            return floor($this->total) - $this->payments->sum('amount'); 
        });
    }

    public function updatePaymentStatus()
    {
        $this->load('items');
        if (floor($this->total) == $this->dueAmount) {
            $this->update(['payment_status' => Invoice::STATUS_UNPAID]);
        } else if ($this->dueAmount > 0) {
            $this->update(['payment_status' => Invoice::STATUS_PARTIALLY_PAID]);
        } else if ($this->dueAmount <= 0) {
            $this->update(['payment_status' => Invoice::STATUS_PAID]);
        }
    }

    public function scopeDueInvoices(Builder $query)
    {
        $query->whereDate('due_date', '<', Carbon::today()->toDateString())->where('payment_status', '!=', Invoice::STATUS_PAID);
    }
}
