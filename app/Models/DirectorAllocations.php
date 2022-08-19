<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DirectorAllocations extends Model
{
    use HasAttributes;

    use SoftDeletes;

    protected $fillable = [
        'paynumber',
        'allocation',
        'quantity',
        'balance',
        'used',
        'extra'
    ];
}
