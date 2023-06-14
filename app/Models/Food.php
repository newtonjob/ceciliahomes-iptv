<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'food';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    public function imagePath(): Attribute
    {
        return Attribute::set(function ($value, $attributes) {
            if (! str($value)->is('http*')) {
                return $value;
            }

            return basename($new = $value.'.jpg') == basename($old = $attributes['imagePath']) ? $old : $new;
        });
    }

    /**
     * Scope the query to include only foods whose image needs to be downloaded locally.
     */
    public function scopeNeedsImageDownload(Builder $builder)
    {
        $builder->where('imagePath', 'like', 'http%');
    }
}
