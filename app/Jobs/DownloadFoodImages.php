<?php

namespace App\Jobs;

use App\Models\Food;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\File;

class DownloadFoodImages
{
    use Dispatchable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Food::needsImageDownload()->each(function (Food $food) {
            $basename = basename($food->imagePath);

            File::put("/usr/local/mstomcat/webapps/iptv2/res/custom/food/$basename",
                file_get_contents($food->imagePath)
            );

            $food->update(['imagePath' => "../res/custom/food/$basename"]);
        });
    }
}
