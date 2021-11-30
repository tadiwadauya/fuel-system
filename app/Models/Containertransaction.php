<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContainerTransaction extends Model
{
    use SoftDeletes;

    protected $table = 'container_transactions';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that are hidden.
     *
     * @var array
     */
    protected $hidden = [
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'client',
        'container',
        'batch',
        'concapacity',
        'conrate',
        'b_before',
        'b_after',
        'created_at'
    ];

}
