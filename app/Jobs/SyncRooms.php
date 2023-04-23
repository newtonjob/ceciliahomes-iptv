<?php

namespace App\Jobs;

use App\Models\Client;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;

class SyncRooms
{
    use Dispatchable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = Http::cecilia()->get('/rooms')->object();

        collect($response->data)->each(function ($room) {
            Client::updateOrCreate(['id' => $room->id], $this->prepareForUpdate($room));
        });
    }

    public function prepareForUpdate($room): array
    {
        // UPDATE `rooms` SET created_at = updated_at;

        return collect([
            'name'        => $room->name,
            'versionCode' => '20700093',
            'versionText' => '2.7.0.93',
            'timeMills'   => Date::make($room->created_at)->unix(),
        ])->when($room->current_reservation, fn ($collection, $reservation) => $collection->merge([
            'money'         => 0,
            'checkPerson'   => $name = $reservation->user->name,
            'checkinTime'   => Date::make($reservation->checkin_at)->unix(),
            'welcomeWord'   => $welcome = (string) view('welcome', compact('name')),
            'welcomeWordEn' => $welcome
        ]), fn ($collection) => $collection->merge([
            'money'         => 0,
            'checkPerson'   => '',
            'checkinTime'   => 0,
            'welcomeWord'   => '',
            'welcomeWordEn' => ''
        ]))->all();
    }
}
