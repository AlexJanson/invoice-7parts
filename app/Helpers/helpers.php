<?php

namespace App\Helpers;

function format_money_pdf($money)
{
    $money = $money / 100;

    $currency = (object) [
        "name" => "Euro",
        "code" => "EUR",
        "symbol" => "€",
        "precision" => "2",
        "thousand_separator" => ".",
        "decimal_separator" => ",",
        "swap_currency_symbol" => true
    ];

    $format_money = number_format(
        $money,
        $currency->precision,
        $currency->decimal_separator,
        $currency->thousand_separator
    );

    $currency_with_symbol = '';
    if (!$currency->swap_currency_symbol) {
        $currency_with_symbol = $format_money . '<span>' . $currency->symbol . '</span>';
    } else {
        $currency_with_symbol = '<span>' . $currency->symbol . ' </span>' . $format_money;
    }

    return $currency_with_symbol;
}

function format_money_pdf_string($money) {
    $money = $money / 100;

    $currency = (object) [
        "name" => "Euro",
        "code" => "EUR",
        "symbol" => "€",
        "precision" => "2",
        "thousand_separator" => ".",
        "decimal_separator" => ",",
        "swap_currency_symbol" => true
    ];

    $format_money = number_format(
        $money,
        $currency->precision,
        $currency->decimal_separator,
        $currency->thousand_separator
    );

    $currency_with_symbol = '';
    if (!$currency->swap_currency_symbol) {
        $currency_with_symbol = $format_money . ' ' . $currency->symbol ;
    } else {
        $currency_with_symbol = $currency->symbol . ' ' . $format_money;
    }

    return $currency_with_symbol;
}