<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add New Role
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <form method="POST" action="{{ route('admin.roles.store') }}">
                        @csrf

                        {{-- Input Nama Role --}}
                        <div class="mb-6">
                            <label for="name"
                                   class="block text-sm font-medium text-gray-700 mb-1">
                                Role Name
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="Contoh: supervisor"
                                   class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Checkbox Permission (dikelompokkan berdasarkan kata terakhir) --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Permissions
                            </label>

                            @foreach ($permissions as $group => $groupPermissions)
                                <div class="mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                                    {{-- Judul Group --}}
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">
                                        {{ $group }} MANAGEMENT
                                    </p>

                                    {{-- Checkbox list per group --}}
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach ($groupPermissions as $permission)
                                            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                                <input type="checkbox"
                                                       name="permissions[]"
                                                       value="{{ $permission->name }}"
                                                       class="rounded border-gray-300 text-blue-600"
                                                       {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                                                {{ $permission->name }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            @error('permissions')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex gap-3">
                            <button type="submit"
                                    class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 font-medium text-sm">
                                Save Role
                            </button>
                            <a href="{{ route('admin.roles.index') }}"
                               class="bg-gray-100 text-gray-700 px-5 py-2 rounded-md hover:bg-gray-200 font-medium text-sm">
                                Cancel
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
