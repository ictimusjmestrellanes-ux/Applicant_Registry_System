<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('slug')->get();

        return view('roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:255|regex:/^[a-z][a-z0-9_]*$/|unique:roles,slug',
            'label' => 'required|string|max:255',
        ]);

        $validated['slug'] = strtolower($validated['slug']);

        Role::create($validated);

        return redirect()->route('roles.index')->with('success', 'Role added successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
