<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('key')->get();

        return view('permissions.index', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|regex:/^[a-z][a-z0-9_]*$/|unique:permissions,key',
            'label' => 'required|string|max:255',
        ]);

        $validated['key'] = strtolower($validated['key']);

        Permission::create($validated);

        return redirect()->route('permissions.index')->with('success', 'Permission added successfully.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
