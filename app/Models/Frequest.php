<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Frequest extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'frequests';

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

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_type',
        'employee',
        'quantity',
        'ftype',
        'amount',
        'status',
        'approved_by',
        'approved_when',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'request_type'                         => 'string',
        'employee'                         => 'string',
        'quantity'                         => 'string',
        'ftype'                         => 'string',
        'amount'                        => 'string',
        'status'                         => 'string',
        'approved_by'                         => 'string',
        'approved_when'                         => 'string',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User', 'paynumber', 'employee');
    }
}
