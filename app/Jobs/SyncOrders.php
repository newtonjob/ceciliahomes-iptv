<?php

namespace App\Jobs;

use App\Models\Food;
use App\Models\FoodOrder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SyncOrders
{
    use Dispatchable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $syncedOrders = Cache::get('synced-orders', collect());

        FoodOrder::with('foods')->whereKeyNot($syncedOrders)
            ->each(function (FoodOrder $foodOrder) use (&$syncedOrders) {
                $products = $foodOrder->foods->mapWithKeys(fn (Food $food) => [
                    $food->id => [
                        'price'    => $food->price,
                        'quantity' => $food->pivot->count,
                    ]
                ]);

                Http::cecilia()->post("/rooms/{$foodOrder->clientId}/orders", ['products' => $products]);

                Cache::put('synced-orders', $syncedOrders = $syncedOrders->add($foodOrder->id));
            });
    }
}
