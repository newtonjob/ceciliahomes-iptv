<?php

namespace App\Jobs;

use App\Models\Vodrecord;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SyncVideoOrders
{
    use Dispatchable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Vodrecord::with('vod')
            ->whereRelation('vod', 'price', '>', 0)
            ->where('clientId', '<=', 30) // Todo: Find a way to avoid this!
            ->whereKeyNot(cache('synced-vodrecord'))
            ->each(function (Vodrecord $vodrecord) {
                Http::cecilia()->post("/rooms/{$vodrecord->clientId}/orders", [
                    'products'     => $this->prepareProductsPayload($vodrecord),
                    'use_iptv_key' => true,
                ]);

                $this->markAsSynced($vodrecord);
            });
    }

    public function prepareProductsPayload(Vodrecord $vodrecord): array
    {
        return [
            $vodrecord->vod->id => [
                'price'    => $vodrecord->vod->price,
                'quantity' => 1
            ]
        ];
    }

    public function markAsSynced(Vodrecord $vodrecord): void
    {
        Cache::put('synced-vodrecord',
            Cache::get('synced-vodrecord', collect())->add($vodrecord->id)
        );
    }
}
