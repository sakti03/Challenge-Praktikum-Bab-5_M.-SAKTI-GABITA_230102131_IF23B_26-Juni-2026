<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Tampilkan daftar semua role beserta jumlah permission-nya.
     */
    public function index()
    {
        $roles = Role::withCount('permissions')->get();

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Tampilkan form untuk membuat role baru.
     */
    public function create()
    {
       
        $permissions = Permission::all()->groupBy(function ($perm) {
            $parts = explode('-', $perm->name);
            return strtoupper(end($parts));
        });

        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Simpan role baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|unique:roles,name|max:100',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $request->name]);

        // Jika ada permission yang dipilih, sync ke role
        if ($request->filled('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role "' . $role->name . '" berhasil dibuat.');
    }

    /**
     * Tampilkan form edit role beserta permission yang sudah dimiliki.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy(function ($perm) {
            $parts = explode('-', $perm->name);
            return strtoupper(end($parts));
        });

        // Ambil nama permission yang dimiliki role ini
        // untuk keperluan pre-check checkbox
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update data role di database.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name'          => 'required|string|unique:roles,name,' . $role->id . '|max:100',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['name' => $request->name]);

        // syncPermissions: hapus semua permission lama,
        // lalu pasang permission yang baru dipilih
        $role->syncPermissions($request->permissions ?? []);

        // Reset cache agar perubahan permission langsung efektif
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role "' . $role->name . '" berhasil diperbarui.');
    }

    /**
     * Hapus role dari database.
     * Role yang masih digunakan user tidak boleh dihapus.
     */
    public function destroy(Role $role)
    {
        // Hitung berapa user yang memakai role ini
        $userCount = $role->users()->count();

        if ($userCount > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Role "' . $role->name . '" tidak dapat dihapus karena masih digunakan oleh ' . $userCount . ' user.');
        }

        $roleName = $role->name;
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role "' . $roleName . '" berhasil dihapus.');
    }
}
