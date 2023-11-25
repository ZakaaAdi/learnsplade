<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\Facades\Toast;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('users.index', [
            'users' => SpladeTable::for(User::class)
                    ->column('name')
                    ->column('email')
                    ->column('gender')
                    ->column('aksi')
                    ->searchInput('name')
                    ->selectFilter('name',[
                      'male' => 'Male',
                      'female' => 'Female',
                    ],
                      noFilterOption: true,
                noFilterOptionLabel: 'All Gender' )
                    ->paginate(15)
                    ],
                  );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        Toast::title('User Data Saved');

        return to_route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('users.edit', [
            'user' => User::find($id),
          ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        if($request->password == '') {
          $user->update([
            'name' => $request->name,
            'email' => $request->email,
          ]);
        } else {
          $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
          ]);
        }
        Toast::title('Data Berhasil Tersimpan')->autoDismiss(3);
        return to_route('users.index');
      }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        Toast::title('Data Berhasil Dihapus')
        ->danger()
        ->autoDismiss(3);
        return to_route('users.index');
    }
}
