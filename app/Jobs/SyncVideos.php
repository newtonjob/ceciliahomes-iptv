<?php

namespace App\Jobs;

use App\Models\Vod;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;

class SyncVideos
{
    use Dispatchable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $videos = Vod::with('type')->where('price', '>', 0)->get()
            ->map->only(['id', 'name', 'price', 'description', 'category']);

        Http::cecilia()->post('/vendors/6/bulk-products', ['products' => $videos])->body();
    }
}
