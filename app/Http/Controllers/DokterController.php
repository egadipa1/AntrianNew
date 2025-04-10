<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DokterController extends Controller
{
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Dokter::all()
        ]);
    }

    /**
     * Display a paginated list of the resource.
     */
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;
    
        $data = Dokter::join('users', 'users.id', '=', 'dokters.dokter_id')
            ->leftJoin('polis', 'polis.id', '=', 'dokters.poli_id')
            ->leftJoin('ruangans', 'ruangans.id', '=', 'dokters.ruangan_id')
            ->when($request->search, function (Builder $query, string $search) {
            $query->where('users.name', 'like', "%$search%")
                ->orWhere('users.email', 'like', "%$search%")
                ->orWhere('polis.name', 'like', "%$search%");
        })->select('users.*', 'polis.name as polis_name', 'ruangans.ruang as ruang', 'dokters.status', 'dokters.id as dokter_id')->latest()->paginate($per);
        foreach ($data as $item) {
            $item->polis_name = $item->polis_name ?: 'Belum Diatur';
            $item->ruang = $item->ruang ?: 'Belum Diatur';
        }
        $no = ($data->currentPage() - 1) * $per + 1;
        foreach ($data as $item) {
            $item->no = $no++;
        }

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * Display the specified resource.
     */
    public function show(Dokter $dokter)
    {
        $dokter['poli_id'] = $dokter?->poli_id;
        $dokter['ruangan_id'] = $dokter?->ruangan_id;

        Log::info($dokter);

        return response()->json([
            'dokter' => $dokter
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dokter $dokter)
    {
        $poli = $request->poli_id;
        $ruangan = $request->ruangan_id;

        // dd($poli,$ruangan);
        $dokter->update([
            'poli_id' => $poli,
            'ruangan_id' => $ruangan,
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
