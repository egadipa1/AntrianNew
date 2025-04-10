<?php

namespace App\Http\Controllers;

use App\Http\Requests\RuanganRequest;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;


class RuanganController extends Controller
{
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Ruangan::all()
        ]);
    }
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no = 0+' . $page * $per);
        $data = Ruangan::when($request->search, function (Builder $query, string $search) {
            $query->where('lantai', 'like', "%$search%")
                ->orWhere('ruang', 'like', "%$search%");
        })->latest()->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    public function store(RuanganRequest $request)
    {
        $validatedData = $request->validated();
        $ruangan = Ruangan::create([
            'ruang' => $validatedData['ruang'],
            'lantai' => $validatedData['lantai'],
        ]);
        return response()->json([
            'success' => true,
            'ruangan' => $ruangan,
        ]);
    }

    public function show(Ruangan $ruangan)
    {
        return response()->json([
            'data' => [
                'name' => $ruangan->name,
                'description' => $ruangan->description,
            ]
        ]);
    }

    public function update(RuanganRequest $request, Ruangan $ruangan)
    {
        $validatedData = $request->validated();
        $ruangan->update([
            'ruang' => $validatedData['ruang'],
            'lantai' => $validatedData['lantai'],
        ]);

        return response()->json([
            'success' => true,
            'ruangan' => $ruangan,
        ]);
    }

    public function destroy(Ruangan $ruangan)
    {
        $ruangan->delete();
        return response()->json([
            'success' => true,
            'message' => 'Ruangan deleted successfully'
        ]);
    }
}
