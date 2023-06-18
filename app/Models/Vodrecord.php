<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Date;

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

    public function syncKey()
    {
        $date = Date::parse($this->date)->toDateString();

        return $this->clientId.$this->name.$date;
    }

    public function vod(): BelongsTo
    {
        return $this->belongsTo(Vod::class, 'vodName', 'name');
    }
}
