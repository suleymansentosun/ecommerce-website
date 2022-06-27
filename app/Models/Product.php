<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \NumberFormatter;

class Product extends Model
{
    use HasFactory;

    /**
     * The categories that belong to the product.
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function presentPrice()
    {
        $formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
        return $formatter->format($this->price/100);
    }

    public function scopeMightAlsoLike($query)
    {
        return $query->inRandomOrder()->take(4);
    }
}
