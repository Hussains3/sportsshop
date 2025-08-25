<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Permission') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.permissions.store') }}">
                        @csrf

                        <div class="grid gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Permission Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus placeholder="e.g., create-products" />
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('Use kebab-case format. Examples: view-products, create-users, manage-roles') }}
                                </p>
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <!-- Permission Categories Examples -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">{{ __('Common Permission Categories') }}</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Dashboard</h4>
                                        <ul class="text-gray-600 dark:text-gray-400 space-y-1">
                                            <li>• view-dashboard</li>
                                            <li>• view-low-stock</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Products</h4>
                                        <ul class="text-gray-600 dark:text-gray-400 space-y-1">
                                            <li>• view-products</li>
                                            <li>• create-products</li>
                                            <li>• edit-products</li>
                                            <li>• delete-products</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Categories</h4>
                                        <ul class="text-gray-600 dark:text-gray-400 space-y-1">
                                            <li>• view-categories</li>
                                            <li>• create-categories</li>
                                            <li>• edit-categories</li>
                                            <li>• delete-categories</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">POS</h4>
                                        <ul class="text-gray-600 dark:text-gray-400 space-y-1">
                                            <li>• access-pos</li>
                                            <li>• create-sales</li>
                                            <li>• view-sales</li>
                                            <li>• view-receipts</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Reports</h4>
                                        <ul class="text-gray-600 dark:text-gray-400 space-y-1">
                                            <li>• view-reports</li>
                                            <li>• view-sales-reports</li>
                                            <li>• export-reports</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Users</h4>
                                        <ul class="text-gray-600 dark:text-gray-400 space-y-1">
                                            <li>• view-users</li>
                                            <li>• create-users</li>
                                            <li>• edit-users</li>
                                            <li>• delete-users</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Create Permission') }}</x-primary-button>
                                <a href="{{ route('admin.permissions') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
