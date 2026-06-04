<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::orderBy('id', 'desc')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::all();  //ambil semua role untuk dropdown
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users'],
            'phone' => ['nullable','string','max:255','unique:users'],
            'address' => ['nullable','string','max:255'],
            'role' => ['required','string','exists:roles,name'],
            'password' => ['required','string','min:8','confirmed'],
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        $user->assignRole($data['role']);

        return redirect()->route('admin.users.index')->with('success', 'User created.');
    }

    public function show(User $user): View
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $roles = Role::all();  //ambil semua role untuk dropdown
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255',"unique:users,email,{$user->id}"],
            'phone' => ["nullable","string","max:255","unique:users,phone,{$user->id}"],
            'address' => ['nullable','string','max:255'],
            'role' => ['required','string','exists:roles,name'],
            'password' => ['nullable','string','min:8','confirmed'],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        $user->syncRoles($request->role);

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }
}