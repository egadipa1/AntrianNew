<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        DB::statement('set @no=0+' . $page * $per);
        $data = Dokter::
            join('users', 'users.id', '=', 'dokters.dokter_id')
            ->leftJoin('polis', 'polis.id', '=', 'dokters.poli_id')
            ->when($request->search, function (Builder $query, string $search) {
            $query->where('users.name', 'like', "%$search%")
                ->orWhere('users.email', 'like', "%$search%")
                ->orWhere('polis.name', 'like', "%$search%");
        })->select('users.*', 'polis.name as polis_name')->latest()->paginate($per);
        foreach ($data as $item) {
            $item->polis_name = $item->polis_name ?: 'Belum Diatur';
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
    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('photo')) {
            $validatedData['photo'] = $request->file('photo')->store('photo', 'public');
        }

        $user = User::create($validatedData);

        $role = Role::findById($validatedData['role_id']);
        $user->assignRole($role);

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user['role_id'] = $user?->role?->id;
        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $validatedData['photo'] = $request->file('photo')->store('photo', 'public');
        } else {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
                $validatedData['photo'] = null;
            }
        }

        $user->update($validatedData);

        $role = Role::findById($validatedData['role_id']);
        $user->syncRoles($role);

        return response()->json([
            'success' => true,
            'user' => $user
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
