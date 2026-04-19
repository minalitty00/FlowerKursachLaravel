<?php

if (!function_exists('format_price')) {
    /**
     * Format price in Russian rubles
     *
     * @param float|int $price
     * @return string
     */
    function format_price($price): string
    {
        return number_format($price, 0, ',', ' ') . ' ₽';
    }
}
