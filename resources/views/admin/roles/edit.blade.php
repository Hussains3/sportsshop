<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Role') }} - {{ $role->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Role Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $role->name)" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label :value="__('Assign Permissions')" />
                                <div class="mt-3 space-y-6">
                                    @foreach($permissions as $group => $groupPermissions)
                                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-3">
                                                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 capitalize">
                                                    {{ $group }} Permissions
                                                </h3>
                                                <button type="button" 
                                                        class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200"
                                                        onclick="toggleGroupPermissions('{{ $group }}')">
                                                    Toggle All
                                                </button>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3" data-group="{{ $group }}">
                                                @foreach($groupPermissions as $permission)
                                                    <label class="inline-flex items-center">
                                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 group-permission-{{ $group }}"
                                                               {{ in_array($permission->name, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ $permission->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('permissions')" />
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                    {{ __('Users with this role') }} ({{ $role->users->count() }})
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($role->users as $user)
                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 rounded-full">
                                            {{ $user->name }}
                                        </span>
                                    @empty
                                        <p class="text-sm text-gray-500 dark:text-gray-400">No users assigned to this role.</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Update Role') }}</x-primary-button>
                                <a href="{{ route('admin.roles') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleGroupPermissions(group) {
            const checkboxes = document.querySelectorAll(`.group-permission-${group}`);
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
        }
    </script>
</x-app-layout>
