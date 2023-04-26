<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FoodOrder extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'foodorder';

    /**
     * The attributes that aren't mass assignable.
     */
    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    public function foods(): BelongsToMany
    {
        return $this->belongsToMany(Food::class, 'foodorderitem', 'orderId', 'foodId')
            ->withPivot('count');
    }
}
