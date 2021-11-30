<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockIssue extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stock_issues';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

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
        'trans_code',
        'employee',
        'voucher',
        'narration',
        'ftype',
        'meter_start',
        'meter_stop',
        'quantity',
        'reg_num',
        'done_by',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'trans_code'                         => 'string',
        'employee'                         => 'string',
        'voucher'                         => 'string',
        'narration'                         => 'string',
        'ftype'                         => 'string',
        'meter_start'                         => 'string',
        'meter_stop'                         => 'string',
        'quantity'                         => 'double',
        'reg_num'                         => 'string',
        'done_by'                         => 'string',
        'created_at'                         => 'string',
        'updated_at'                         => 'string',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User', 'paynumber', 'employee');
    }
}
