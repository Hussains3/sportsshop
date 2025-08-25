<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('User Details') }} - {{ $user->name }}
            </h2>
            @can('edit-users')
                <a href="{{ route('users.edit', $user) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit User
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid gap-6 md:grid-cols-2">
                        <!-- User Information -->
                        <div class="space-y-6">
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('User Information') }}</h3>
                            </div>

                            <div class="flex items-center space-x-4">
                                <div class="h-16 w-16 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                    <span class="text-lg font-medium text-gray-700 dark:text-gray-300">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </span>
                                </div>
                                <div>
                                    <h4 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $user->name }}</h4>
                                    <p class="text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                                </div>
                            </div>

                            <div class="grid gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Email Verified At') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $user->email_verified_at ? $user->email_verified_at->format('M d, Y H:i') : 'Not verified' }}
                                    </dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Member Since') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Last Updated') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $user->updated_at->format('M d, Y H:i') }}
                                    </dd>
                                </div>
                            </div>
                        </div>

                        <!-- Roles and Permissions -->
                        <div class="space-y-6">
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Roles & Permissions') }}</h3>
                            </div>

                            <!-- Roles -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">{{ __('Assigned Roles') }}</dt>
                                <dd class="flex flex-wrap gap-2">
                                    @forelse($user->roles as $role)
                                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            No roles assigned
                                        </span>
                                    @endforelse
                                </dd>
                            </div>

                            <!-- Direct Permissions -->
                            @if($user->permissions->count() > 0)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">{{ __('Direct Permissions') }}</dt>
                                    <dd class="flex flex-wrap gap-2">
                                        @foreach($user->permissions as $permission)
                                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                {{ $permission->name }}
                                            </span>
                                        @endforeach
                                    </dd>
                                </div>
                            @endif

                            <!-- All Permissions via Roles -->
                            @if($user->getAllPermissions()->count() > 0)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">{{ __('All Permissions') }} <span class="text-xs">(via roles + direct)</span></dt>
                                    <dd class="max-h-48 overflow-y-auto">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($user->getAllPermissions()->groupBy(function($permission) {
                                                return explode('-', $permission->name)[1] ?? 'general';
                                            }) as $group => $permissions)
                                                <div class="w-full mb-2">
                                                    <h5 class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1">{{ ucfirst($group) }}</h5>
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($permissions as $permission)
                                                            <span class="px-2 py-1 inline-flex text-xs leading-4 font-medium rounded bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                                                {{ $permission->name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </dd>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-8 flex items-center gap-4">
                        <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                            {{ __('Back to Users') }}
                        </a>
                        @can('edit-users')
                            <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                {{ __('Edit User') }}
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
