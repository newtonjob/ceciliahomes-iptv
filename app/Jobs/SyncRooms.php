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

            $this->deleteAnyExistingClientIfNecessaryFor($room);
        });
    }

    public function prepareForUpdate($room): array
    {
        return collect([
            'name'        => $room->name,
            'versionCode' => '20700093',
            'mac'         => $room->meta->iptv_mac ?? '',
            'versionText' => '2.7.0.93',
            'timeMills'   => Date::make($room->created_at)->unix(),
        ])->when($room->current_reservation, fn ($collection, $reservation) => $collection->merge([
            'enable'        => true,
            'money'         => 0,
            'checkPerson'   => $name = $reservation->user->name,
            'checkinTime'   => Date::make($reservation->checkin_at)->unix(),
            'welcomeWord'   => $welcome = (string) view('welcome', compact('name')),
            'welcomeWordEn' => $welcome
        ]), fn ($collection) => $collection->merge([
            'enable'        => false,
            'money'         => 0,
            'checkPerson'   => '',
            'checkinTime'   => 0,
            'welcomeWord'   => '',
            'welcomeWordEn' => ''
        ]))->all();
    }

    public function deleteAnyExistingClientIfNecessaryFor(object $room): void
    {
        if ($mac = data_get($room, 'meta.iptv_mac')) {
            Client::whereKeyNot($room->id)->whereMac($mac)->delete();
        }
    }
}
