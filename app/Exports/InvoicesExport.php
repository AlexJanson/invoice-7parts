<?php

namespace App\Exports;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Vitorccs\LaravelCsv\Concerns\Exportable;
use Vitorccs\LaravelCsv\Concerns\FromArray;
use Vitorccs\LaravelCsv\Concerns\FromCollection;
use Vitorccs\LaravelCsv\Concerns\FromQuery;
use Vitorccs\LaravelCsv\Concerns\WithHeadings;

use function App\Helpers\format_money_pdf_string;

class InvoicesExport implements FromCollection, WithHeadings
{
    use Exportable;

    public function __construct(public int $year, public int $quarter)
    {
        //
    }

    public function headings(): array
    {
        return ['Factuur', 'Bedrijfsnaam', 'Datum', 'SubTotaal', 'TotaalBTW', 'Totaal', 'Ontvangen', 'Datum ontvangen'];
    }
    
    public function collection()
    {
        $month = ($this->quarter - 1) * 3 + 1;
        $start = Carbon::createFromDate($this->year, $month, 1);
        $end = $start->clone()->addMonth(2)->endOfMonth();

        return Invoice::query()
            ->with('customer')
            ->withTrashed()
            ->whereBetween('invoice_date', [$start, $end])
            ->get()
            ->map(function ($item) {
                $dateReceived = $item->payments->last() == null 
                    ? "" : $item->payments->last()->created_at->isoFormat('Y-MM-DD');

                return [
                    'invoice_number' => $item->invoice_number,
                    'name' => $item->customer->name,
                    'invoice_date' => $item->getRawOriginal('invoice_date'),
                    'subtotal' => format_money_pdf_string($item->subtotal),
                    'tax' => format_money_pdf_string($item->tax),
                    'total' => format_money_pdf_string($item->total),
                    'received' => $item->payment_status,
                    'date_received' => $dateReceived
                ];
            });
    }
}