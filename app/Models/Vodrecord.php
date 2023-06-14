<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vodrecord extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'vodrecord';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    public function vod(): BelongsTo
    {
        return $this->belongsTo(Vod::class, 'vodName', 'name');
    }
}
