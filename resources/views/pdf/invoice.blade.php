@php
    App::setLocale('nl');
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Factuur {{ $invoice->invoice_number }} {{ $invoice->customer->name }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        /* Setting up the font */
        /* @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@300&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@500&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@600&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@700&display=swap'); */

        /* Base */
        html {
            margin: 0px;
            padding: 0px;
            margin-top: 50px;
            margin-bottom: 25px;
        }

        body {
            font-family: 'Nunito';
            margin: 120px 0px;
        }

        /* Preflight styles from tailwindcss */
        blockquote,
        dl,
        dd,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        hr,
        figure,
        p,
        pre {
            margin: 0;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-size: inherit;
            font-weight: inherit;
        }

        ol,
        ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        img,
        svg,
        video,
        canvas,
        audio,
        iframe,
        embed,
        object {
            display: block;
            vertical-align: middle;
        }

        *,
        ::before,
        ::after {
            border-width: 0;
            border-style: solid;
            border-color: white;
        }

        button:focus {
            outline: 1px dotted;
            outline: 5px auto -webkit-focus-ring-color;
        }

        a {
            color: inherit;
            text-decoration: inherit;
        }

        /* Helpers */
        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-xxs {
            font-size: 0.58rem/* 8px */;
            line-height: .5rem/* 12px */;
        }

        .text-xs {
            font-size: 0.75rem/* 12px */;
            line-height: 1rem/* 16px */;
        }

        .text-sm {
            font-size: 0.875rem/* 14px */;
        }

        .text-base {
            font-size: 1rem/* 16px */;
            line-height: 1.5rem/* 24px */;
        }

        .text-lg {
            font-size: 1.125rem/* 18px */;
            line-height: 1rem/* 16px */;
        }

        .text-xl {
            font-size: 1.25rem/* 20px */;
            line-height: 1.75rem/* 28px */;
        }

        .text-2xl {
            font-size: 1.5rem/* 24px */;
            line-height: 2rem/* 32px */;
        }

        .text-3xl {
            font-size: 1.875rem/* 30px */;
            line-height: 2.25rem/* 36px */;
        }

        .font-light {
            font-weight: 300;
        }

        .font-medium {
            font-weight: 500;
        }

        .font-semibold {
            font-weight: 600;
        }

        .font-bold {
            font-weight: 700;
        }

        .leading-3 {
            line-height: .9rem/* 12px */;
        }
        
        .leading-4 {
            line-height: 1rem/* 16px */;
        }

        .leading-7 {
            line-height: 1.75rem/* 28px */;
        }

        .pl-3 {
            padding-left: 0.75rem/* 12px */;
        }

        .pl-12 {
            padding-left: 3rem/* 48px */;
        }

        .pr-20 {
            padding-right: 5rem/* 80px */;
        }

        .mb-2 {
            margin-bottom: 0.5rem/* 8px */;
        }

        .mt-3 {
            margin-top: 0.75rem/* 12px */;
        }

        .mt-6 {
            margin-top: 1.5rem/* 24px */;
        }

        .align-top {
            vertical-align: top;
        }

        .text-gray-400 {
            color: rgb(156 163 175);
        }

        .text-gray-600 {
            color: rgb(75 85 99);
        }

        .text-indigo-600 {
            color: rgb(79 70 229);
        }

        .text-cyan-700 {
            color: rgb(14 116 144);
        }

        .bg-indigo-50 {
            background-color: rgb(238 242 255);
        }

        .page-break {
            page-break-before: always;
        }

        .absolute {
            position: absolute;
        }

        /* Header */
        .header-container {
            position: fixed;
            width: 100%;
            height: 161px;
            left: 0px;
            top: -60px;
        }

        .header-section-left { 
            padding-left: 35px;
            /* display: inline-block; */
            width: 1px;
        }

        .header-logo {
            padding-top: 30px;
            position: absolute;
        }

        .header-section-center {
            text-align: left;
            padding-top: 85px;
            padding-left: 50px;
        }

        .header-section-right {
            display: inline-block;
            width: 100%;
            float: right;
            padding: 20px 35px 0 0;
            text-align: right;
        }

        /* Shipping address */
        .shipping-address-container {
            padding: 0 35px 0 0;
        }

        /* Period */
        .period {
            padding: 10px 35px 10px 35px;
        }

        /* Items */
        .items-table {
            padding: 0px 35px;
            border-spacing: 0;
            page-break-before: avoid;
            page-break-after: auto;
        }

        .item-row > td {
            padding-bottom: 1rem;
        }

        body > main > table > tbody.items-body > tr:nth-child(1) > td {
            padding-top: 1rem;
        }

        tr.items-table-heading th {
            border-bottom: 0.5px solid;
            border-color: rgb(209 213 219);
            padding-bottom: 10px;
        }

        .items-body {
            border-bottom: 0.5px solid;
            border-color: rgb(209 213 219);
            /* padding-bottom: 20px; */
        }

        .item-row {
            font-size: 12px;
            line-height: 12px;
        }

        /* Total */
        .total-container {
            padding: 10px 35px 0px 35px;
        }

        .total-table {
            padding-top: 10px;
            float: right;
            width: auto;
            page-break-inside: avoid;
            page-break-before: auto;
            page-break-after: auto;
        }

        .total-table td {
            line-height: 1.1rem !important;
        }
        
        /* Notes */
        .notes {
            padding: 10px 35px 0px 35px;
            width: 400px;
            page-break-inside: avoid;
        }

        /* Footer */
        .footer-container {
            position: fixed;
            bottom: -25px;
            left: 0px;
            right: 0px;
            padding: 10px 35px;
        }
    </style>
</head>

<body>
    <header class="header-container bg-indigo-50">
        <table width="100%">
            <tr>
                <td class="header-section-left">
                    <img class="header-logo" src="data:image/png;base64,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" alt="Logo" style="height: 110px;">
                </td>

                @if(is_null($noAddress))
                    <td class="header-section-center">
                        <p>Iet van Feggelenlaan 16</p>
                        <p>1111 WV Diemen</p>
                    </td>
                @endif

                <td class="header-section-right invoice-details-container font-light">
                    <h1 class="font-semibold text-3xl leading-7">Factuur</h1>
                    <a class="text-sm" href="tel:+31202358823">+31 20 23 588 23</a>
                    <p class="text-sm">info@7parts.nl</p>
                    <p class="text-sm">www.7parts.nl</p>
                </td>
            </tr>
        </table>
    </header>
       
    <footer class="footer-container bg-indigo-50 font-light text-sm leading-3">

        <table width="85%">
            <tr>
                <td>
                    <p>BTW nr:</p>
                    <p>KVK nr:</p> 
                </td>
                <td>
                    <p>8154.59.166.B.01</p>
                    <p>34.244.679</p>
                </td>
                <td>
                    <p>ING:</p>
                    <p>Rabobank:</p>
                </td>
                <td>
                    <p>39.76.854</p>
                    <p>32.45.78.105</p>
                </td>
                <td>
                    <p>IBAN: NL20 INGB 0003 9768 54</p>
                    <p>IBAN: NL46 RABO 0324 5781 05</p>
                </td>
            </tr>
        </table>

        <p class="text-xxs font-light mt-3">Op al onze aanbiedingen, aan ons verstrekte opdrachten en met ons gesloten overeenkomsten zijn de algemene verkoop-, leverings- en betalingsvoorwaarden van toepassing <br> in hun meest recente vorm als gedeponeerd bij de Kamer van Koophandel en fabrieken te Amsterdam. Op eerste verzoek zal U kosteloos een exemplaar worden toegezonden.</p>
    </footer>

    <main>
        <div class="shipping-address-container">
            <table width="100%">
                <tr>
                    <td width="45%" class="align-top font-light leading-3" style="padding-left: 35px;">
                        <h1 class="text-2xl font-bold mb-2" style="visibility: hidden;">{{ $invoice->customer->name }}</h1>
                        <p class="text-lg">Factuurnummer: <span class="font-bold">{{ $invoice->invoice_number }}</span></p>
                        @isset($invoice->reference)
                            <p class="text-lg leading-4">Referentie: {{ $invoice->reference }}</p>
                        @endisset
                        <p class="text-lg leading-4">Datum: {{ (new \Carbon\Carbon($invoice->getRawOriginal('invoice_date')))->isoFormat("D MMMM, YYYY") }}</p>
                    </td>
                    <td width="10%"></td>
                    <td width="45%" class="align-top font-light leading-3">
                        <h1 class="text-2xl font-bold mb-2">{{ $invoice->customer->name }}</h1>
                        @isset($invoice->contact)
                            <p class="text-lg">TAV: {{ $invoice->contact->name }}</p>
                        @endisset
                        <p class="text-lg">{{ $invoice->customer->shippingAddress->address }}</p>
                        <p class="text-lg">{{ $invoice->customer->shippingAddress->zipcode }} {{ $invoice->customer->shippingAddress->city }}</p>
                        @if ($invoice->customer->shippingAddress->country != 'Netherlands' && $invoice->customer->shippingAddress->country != 'The Netherlands' && $invoice->customer->shippingAddress->country != 'Nederland')
                            <p class="text-lg">{{ $invoice->customer->shippingAddress->country }}</p>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <div class="period">
            <h3 class="font-light">
                Periode:
                <span class="text-base font-semibold pl-12">{{ $invoice->term }} {{ $invoice->year }}</span>
            </h3>
        </div>
    
        <table width="100%" class="items-table">
            <tr class="items-table-heading text-cyan-700 border-gray-300">
                <th class="text-left font-medium">Item</th>
                <th width="12%" class="text-right font-medium" style="padding-right: 10px;">Aantal</th>
                <th width="10%" class="text-right font-medium" style="padding-right: 10px;">Prijs</th>
                @if ($invoice->items->filter(function ($item) {
                        return $item->discount > 0;
                    })->isNotEmpty())
                    <th width="8%" class="text-right font-medium">Korting</th>
                @endif
                {{-- <th width="8%" class="text-right font-medium">BTW</th> --}}
                <th width="12%" class="text-right font-medium">Totaal</th>
            </tr>
            <tbody class="items-body">
                @php
                    $index = 0
                @endphp
                @foreach ($invoice->items as $item)
                    <tr class="item-row">
                        <td style="padding-right: 5px;">
                            <span class="text-sm font-semibold">{{ $item->name }}</span><br>
                            <span class="text-gray-600">{{ $item->description }}</span>
                        </td>
                        <td class="text-right text-sm" style="padding-right: 10px;">
                            {{ number_format($item->quantity / 100, 2, ",", "") }} {{ $item->unit === 'PIECES' ? 'st.' : 'u.' }} 
                        </td>
                        <td class="text-right text-sm" style="padding-right: 10px;">
                            {!! \App\Helpers\format_money_pdf($item->price) !!}
                        </td>
                        @if ($invoice->items->filter(function ($item) {
                        return $item->discount > 0;
                    })->isNotEmpty())
                        <td class="text-right text-sm">
                            {{ $item->discount }} %
                        </td>
                        @endif
                        {{-- <td class="text-right text-sm">
                            {{ $item->tax }} %
                        </td> --}}
                        <td class="text-right text-sm">
                            {!! \App\Helpers\format_money_pdf($item->subtotal * (1 - $item->discount / 100)) !!}
                        </td>
                    </tr>
                    @php
                        $index += 1
                    @endphp
                @endforeach
            </tbody>
        </table>
    
        <div class="total-container">
            <table width="100%" class="total-table">
                <tbody>
                    <tr>
                        <td class="pr-20 font-medium text-sm">Subtotaal</td>
                        <td class="text-right text-sm">{!! \App\Helpers\format_money_pdf($invoice->subtotal) !!}</td>
                    </tr>
                    
                    <tr>
                        <td class="font-medium text-sm">BTW</td>
                        <td class="text-right text-sm">{!! \App\Helpers\format_money_pdf($invoice->tax) !!}</td>
                    </tr>
                    
                    {{-- TODO: different discount types. --}}
                    {{-- @if($invoice->discount_val > 0)
                        <tr>
                            <td class="font-medium text-sm">Korting</td>
                            <td class="text-right text-sm">{!! \App\Helpers\format_money_pdf($invoice->discount) !!}</td>
                        </tr>
                    @endif --}}

                    <tr>
                        <td class="font-semibold text-xl text-cyan-700">Totaal</td>
                        <td class="font-semibold text-right text-xl text-indigo-600">{!! \App\Helpers\format_money_pdf($invoice->total) !!}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    
        <div class="notes">
            @isset($invoice->comments)
                <h3 class="font-medium">Opmerkingen</h3>
                <p class="text-sm font-light leading-3 mt-3">{{ $invoice->comments }}</p>
            @endisset
        </div>

        <h2 class="text-xl font-light text-center absolute" style="bottom: 100px; width: 100%;">Gelieve binnen 14 dagen betalen</h2>
    </main>
</body>

</html>