<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grant extends Model
{

    protected $fillable = [
        'title',
        'description',
        'country',
        'category',
        'funding_amount',
        'deadline',
        'source_link',
        'relevance_score',
    ];

}
