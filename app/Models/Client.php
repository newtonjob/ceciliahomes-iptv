<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'client';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;
}
