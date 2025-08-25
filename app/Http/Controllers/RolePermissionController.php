<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-roles', ['only' => ['roles', 'createRole', 'storeRole', 'editRole', 'updateRole', 'destroyRole']]);
        $this->middleware('permission:manage-permissions', ['only' => ['permissions', 'createPermission', 'storePermission', 'destroyPermission']]);
    }

    public function roles()
    {
        $roles = Role::with('permissions')->paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    public function createRole()
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode('-', $permission->name)[1] ?? 'general';
        });
        return view('admin.roles.create', compact('permissions'));
    }

    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'permissions' => 'array'
        ]);

        $role = Role::create(['name' => $request->name]);
        
        if ($request->has('permissions')) {
            $role->givePermissionTo($request->permissions);
        }

        return redirect()->route('admin.roles')->with('success', 'Role created successfully.');
    }

    public function editRole(Role $role)
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode('-', $permission->name)[1] ?? 'general';
        });
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function updateRole(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.roles')->with('success', 'Role updated successfully.');
    }

    public function destroyRole(Role $role)
    {
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles')->with('error', 'Cannot delete role that is assigned to users.');
        }

        $role->delete();
        return redirect()->route('admin.roles')->with('success', 'Role deleted successfully.');
    }

    public function permissions(Request $request)
    {
        $query = Permission::with('roles');

        // Search filter
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $permissions = $query->paginate(20)->withQueryString();
        return view('admin.permissions.index', compact('permissions'));
    }

    public function createPermission()
    {
        return view('admin.permissions.create');
    }

    public function storePermission(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions'
        ]);

        Permission::create(['name' => $request->name]);

        return redirect()->route('admin.permissions')->with('success', 'Permission created successfully.');
    }

    public function destroyPermission(Permission $permission)
    {
        if ($permission->roles()->count() > 0) {
            return redirect()->route('admin.permissions')->with('error', 'Cannot delete permission that is assigned to roles.');
        }

        $permission->delete();
        return redirect()->route('admin.permissions')->with('success', 'Permission deleted successfully.');
    }
}
