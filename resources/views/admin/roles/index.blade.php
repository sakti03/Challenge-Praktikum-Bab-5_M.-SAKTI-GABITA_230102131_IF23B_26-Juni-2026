<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Role Management
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    {{-- Pesan Sukses --}}
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Pesan Error --}}
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Header Tabel --}}
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-700">Daftar Role</h3>
                        <a href="{{ route('admin.roles.create') }}"
                           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm font-medium">
                            + Add Role
                        </a>
                    </div>

                    {{-- Tabel Role --}}
                    <table class="w-full text-sm text-left border border-gray-200 rounded">
                        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="p-3 w-12">No</th>
                                <th class="p-3">Role Name</th>
                                <th class="p-3">Permissions</th>
                                <th class="p-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($roles as $index => $role)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 text-gray-500">{{ $index + 1 }}</td>
                                    <td class="p-3 font-medium text-gray-800 capitalize">
                                        {{ $role->name }}
                                    </td>
                                    <td class="p-3">
                                        <span class="inline-block px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full font-medium">
                                            {{ $role->permissions_count }} permission
                                        </span>
                                    </td>
                                    <td class="p-3 flex items-center gap-3">
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('admin.roles.edit', $role) }}"
                                           class="text-blue-600 hover:underline font-medium">
                                            Edit
                                        </a>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('admin.roles.destroy', $role) }}"
                                              method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus role \'{{ $role->name }}\'?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:underline font-medium">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-6 text-center text-gray-400">
                                        Belum ada role yang dibuat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
