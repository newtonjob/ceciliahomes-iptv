<?php

namespace App\Jobs;

use App\Models\Food;
use App\Models\Foodtype;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;

class SyncFoods
{
    use Dispatchable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $products = Http::cecilia()->get('/vendors/5/products')->object()->data;

        collect($products)->pluck('category')->unique()->filter()->each(function ($category) {
            Foodtype::updateOrCreate(['id' => $category->id], ['name' => $category->name]);
        });

        collect($products)->each(function ($product) {
            Food::updateOrCreate(['id' => $product->id], [
                'name'      => $product->name,
                'price'     => $product->price,
                'content'   => $product->description,
                'imagePath' => $product->image->src ?? '',
                'typeId'    => $product->category->id ?? 0,
            ]);
        });
    }
}
