<?php

function set_active($uri, $output = 'active')
{
    if( is_array($uri) ) {
        foreach ($uri as $u) {
            if (Route::is($u)) {
                return $output;
            }
        }
    } else {
        if (Route::is($uri)){
            return $output;
        }
    }
}

if (!function_exists('price_format')) {
    /**
     * @param null $symbol
     */
    function price_format($amount, bool $useDecimal = false, $symbol = null): string
    {
        if (!$symbol) {
            $symbol = 'Rp ';
        }

        $isNegative = false;
        if ($amount < 0) {
            $isNegative = true;
            $amount = abs($amount);
        }

        $parts = [];
        $parts['symbol'] = $symbol;
        $decimal = $useDecimal ? 2 : 0;
        $parts['amount'] = number_format($amount, $decimal, ',', '.');

        $price = implode('', $parts);

        return $isNegative ? "($price)" : $price;
    }
}