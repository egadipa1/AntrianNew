<?php

namespace App\Listeners;

use App\Events\DokterStatus;
use App\Models\Dokter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DokterStatusListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DokterStatus $event): void
    {
        $dokter = $event->dokter;

        $poli = $dokter->poli_id->exist();
        $ruang = $dokter->ruangan_id->exist();

        if($poli && $ruang){
            Dokter::update([
                'status' => 'Nonaktif'
            ]);
        }
    }
}
