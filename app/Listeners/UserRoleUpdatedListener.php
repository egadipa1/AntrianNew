<?php

namespace App\Listeners;

use App\Events\UserRoleUpdated;
use App\Models\Dokter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UserRoleUpdatedListener
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
    public function handle(UserRoleUpdated $event): void
    {
        $user = $event->user;

        $currentRole = $user->roles()->pluck('name')->first();

        
        // Jika user menjadi Dokter dan belum ada di tabel dokter, tambahkan
        if ($currentRole === 'Dokter' && !$user->dokter) {
            Dokter::create([
                'dokter_id' => $user->id,
                'poli_id' => null, // Atur poli nanti
            ]);  
        }

        
        // Jika user tidak lagi menjadi Dokter, hapus dari tabel dokter
        if ($currentRole !== 'Dokter' && $user->dokter) {
            $user->dokter->delete();
        }
    }
}
