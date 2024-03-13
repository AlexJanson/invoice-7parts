<?php

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\PaymentsController;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/login');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        request()->validate([
            'column' => ['in:number,date,customer,paid,due-amount,total'],
            'direction' => ['in:ASC,DESC,PAID,PARTIALLY PAID,UNPAID'],
            'archived' => ['in:true,false']
        ]);

        $query = Invoice::query()->dueInvoices();

        if (request()->has('archived'))
            $query->withTrashed();

        $hasColumnAndDirection = request()->has(['column', 'direction']);
        if ($hasColumnAndDirection) {
            $column = [
                'number' => 'invoice_number',
                'date' => 'invoice_date',
                'customer' => 'customers.name',
                'paid' => 'payment_status',
                'due-amount' => 'due_amount'
            ][request('column')] ?? request('column');
            
            if (!in_array($column, ['payment_status', 'due_amount', 'total']))
                $query->orderBy($column, request('direction'));
        }

        $query->select(['invoices.*', 'customers.slug', 'customers.name'])->join('customers', 'invoices.customer_id', '=', 'customers.id');
        if (request('search'))
            $query->where('invoice_number', 'LIKE', '%'.request('search').'%')
                    ->orWhere('customers.name', 'LIKE', '%'.request('search').'%')
                    ->orWhere('payment_status', 'LIKE', '%'.request('search').'%');

        $dueInvoices = $query->get();
        $dueInvoices->each(function ($invoice) {
            $invoice->append(['total', 'due_amount']);
        });

        if ($hasColumnAndDirection)
            $dueInvoices = $dueInvoices->sortBy(function ($invoice) use ($column) {
                if (in_array($column, ['total', 'due_amount'])) {
                    return $invoice->$column;
                } else if ($column == 'payment_status') {
                    return $invoice->$column == request('direction') ? -1 : 1;
                }
            }, SORT_REGULAR, request('direction') === 'DESC' ? true : false);

        return Inertia::render('Dashboard/Index', [
            'dueInvoices' => $dueInvoices->values()->paginate(8),
            'customers' => Customer::count(),
            'invoices' => Invoice::count(),
            'products' => Product::count(),
            'dueAmount' => Invoice::all()->sum('due_amount')
        ]);
    })->name('dashboard')->withTrashed();

    Route::get('/customers', [CustomersController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomersController::class, 'create'])->name('customer.create');
    Route::get('/customers/{customer:slug}', [CustomersController::class, 'show'])->name('customer.show')->withTrashed();
    Route::get('/customers/{customer:slug}/edit', [CustomersController::class, 'edit'])->name('customer.edit');
    Route::put('/customers/{customer:slug}', [CustomersController::class, 'update'])->name('customer.update');
    Route::post('/customers', [CustomersController::class, 'store'])->name('customer.store');
    Route::patch('/customers/{customer:slug}/restore', [CustomersController::class, 'restore'])->name('customer.restore')->withTrashed();
    Route::delete('/customers/{customer:slug}', [CustomersController::class, 'destroy'])->name('customer.destroy');

    Route::get('/customers/{customer:id}/invoices', [CustomersController::class, 'getInvoices'])->name('customer.invoices');
    Route::get('/customers/{customer:id}/contacts', [CustomersController::class, 'getContacts'])->name('customer.contacts');

    Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductsController::class, 'create'])->name('product.create');
    Route::get('/products/{product:slug}', [ProductsController::class, 'show'])->name('product.show');
    Route::get('/products/{product:slug}/edit', [ProductsController::class, 'edit'])->name('product.edit');
    Route::put('/products/{product:slug}', [ProductsController::class, 'update'])->name('product.update');
    Route::post('/products', [ProductsController::class, 'store'])->name('product.store');
    Route::delete('/products/{product:slug}', [ProductsController::class, 'destroy'])->name('product.destroy');

    Route::get('/invoices', [InvoicesController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create', [InvoicesController::class, 'create'])->name('invoice.create');
    Route::get('/invoices/{invoice:invoice_number}', [InvoicesController::class, 'show'])->name('invoice.show')->withTrashed();
    Route::get('/invoices/{invoice:invoice_number}/edit', [InvoicesController::class, 'edit'])->name('invoice.edit');
    Route::put('/invoices/{invoice:invoice_number}', [InvoicesController::class, 'update'])->name('invoice.update');
    Route::post('/invoices', [InvoicesController::class, 'store'])->name('invoice.store');
    Route::patch('/invoices/{invoice:invoice_number}', [InvoicesController::class, 'restore'])->name('invoice.restore')->withTrashed();
    Route::delete('/invoices/{invoice:invoice_number}', [InvoicesController::class, 'destroy'])->name('invoice.destroy');

    /* Invoice PDF's */
    Route::get('/invoices/{invoice:invoice_number}/download/{locale?}', [InvoicesController::class, 'download'])->name('invoice.download');
    Route::get('/invoices/{invoice:invoice_number}/stream/{locale?}', [InvoicesController::class, 'stream'])->name('invoice.stream');

    Route::get('/invoices/{invoice:invoice_number}/payments/create', [InvoicesController::class, 'createPayment'])->name('payment.create');
    Route::get('/payments/{payment:id}', [PaymentsController::class, 'show'])->name('payment.show');
    Route::get('/payments/{payment:id}/edit', [PaymentsController::class, 'edit'])->name('payment.edit');
    Route::post('/payments', [PaymentsController::class, 'store'])->name('payment.store');
    Route::delete('/payments/{payment:id}', [PaymentsController::class, 'destroy'])->name('payment.destroy');

    Route::get('/pdf', function() {
        $pdf = Pdf::loadView('pdf.reminder', [
            "invoice" => Invoice::with(['customer' => function ($query) {
                $query->withTrashed()->with('shippingAddress');
            }])->find(2),
            "noAddress" => null
        ]);
        return $pdf->stream();
    });

    Route::get('/no-address/{invoice:invoice_number}', function(Invoice $invoice) {
        $pdf = Pdf::loadView('pdf.invoice', [
            "invoice" => Invoice::with(['customer' => function ($query) {
                $query->withTrashed()->with('shippingAddress');
            }])->find($invoice->id),
            "noAddress" => true
        ]);
        return $pdf->stream();
    });
});
