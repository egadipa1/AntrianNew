<?php

namespace App\Http\Controllers;

use App\Http\Requests\PoliRequest;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;


class PoliController extends Controller
{
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Poli::all()
        ]);
    }
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);
        $data = Poli::when($request->search, function (Builder $query, string $search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%");
        })->latest()->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    public function store(PoliRequest $request)
    {
        $validatedData = $request->validated();
        $poli = Poli::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
        ]);
        return response()->json([
            'success' => true,
            'poli' => $poli,
        ]);
    }

    public function show(Poli $poli)
    {
        return response()->json([
            'role' => [
                'name' => $poli->name,
                'description' => $poli->description,
            ]
        ]);
    }

    public function update(PoliRequest $request, Poli $poli)
    {
        $validatedData = $request->validated();
        $poli->update([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
        ]);

        return response()->json([
            'success' => true,
            'poli' => $poli,
        ]);
    }

    public function destroy(Poli $poli)
    {
        $poli->delete();
        return response()->json([
            'success' => true,
            'message' => 'Poli deleted successfully'
        ]);
    }
}
