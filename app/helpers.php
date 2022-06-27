<?php

function presentPrice($price)
{
    $formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
    return $formatter->format(number_format((float)str_replace(',', '', $price)/100, 2, '.', ''));
}

function setActiveGroup($group, $output = 'active')
{
    return request()->query('group') == $group ? $output : '';
}

function productImage($path)
{
    return $path && file_exists('storage/'.$path) ? asset('storage/'.$path) : asset('img/not-found.jpg');
}