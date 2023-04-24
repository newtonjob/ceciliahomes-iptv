<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'food';

    /**
     * The attributes that aren't mass assignable.
     */
    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;
}
