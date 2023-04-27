<?php

namespace App\Jobs;

use App\Models\Food;
use App\Models\FoodOrder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Collection;
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
        FoodOrder::with('foods')->whereKeyNot(cache('synced-orders'))->forPage()
            ->each(function (FoodOrder $foodOrder) {
                Http::cecilia()->post("/rooms/{$foodOrder->clientId}/orders", [
                    'products' => $this->prepareProductsPayload($foodOrder)
                ]);

                $this->markAsSynced($foodOrder);
            });
    }

    public function prepareProductsPayload(FoodOrder $foodOrder): Collection
    {
        return $foodOrder->foods->mapWithKeys(fn (Food $food) => [
            $food->id => [
                'price'    => $food->price,
                'quantity' => $food->pivot->count,
            ]
        ]);
    }

    public function markAsSynced(FoodOrder $foodOrder): void
    {
        Cache::put('synced-orders',
            Cache::get('synced-orders', collect())->add($foodOrder->id)
        );
    }
}
