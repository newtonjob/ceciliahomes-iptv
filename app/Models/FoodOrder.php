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
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    public function foods(): BelongsToMany
    {
        return $this->belongsToMany(Food::class, 'foodorderitem', 'orderId', 'foodId')
            ->withPivot('count');
    }
}
