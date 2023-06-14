<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Vod extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'vod';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    public function description(): Attribute
    {
        return Attribute::get(fn () => $this->introduction);
    }

    public function category(): Attribute
    {
        return Attribute::get(fn () => $this->type->name);
    }

    public function type()
    {
        return $this->belongsTo(VodType::class, 'typeId');
    }
}
