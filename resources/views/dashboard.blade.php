<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Store Dashboard') }}
            </h2>
            <span class="text-sm text-gray-600 dark:text-gray-400">{{ now()->format('l, F j, Y') }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Inventory Alerts Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-red-600 dark:text-red-400">
                                    {{ __('Inventory Alerts') }}
                                </h3>
                                <div class="mt-2 space-y-2">
                                    <div class="flex items-center">
                                        <span class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $outOfStockCount }}</span>
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Out of Stock</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $lowStockProduct }}</span>
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Low Stock</span>
                                    </div>
                                </div>
                                @if($lowStockProduct > 0 || $outOfStockCount > 0)
                                    <a href="{{ route('lowStockProducts') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-red-600 dark:bg-red-500 border border-transparent rounded-md text-xs text-white uppercase tracking-widest hover:bg-red-700 dark:hover:bg-red-400 transition">
                                        {{ __('View Details') }}
                                    </a>
                                @endif
                            </div>
                            <svg class="h-12 w-12 text-red-200 dark:text-red-800" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20zm0 2a8 8 0 1 0 0 16 8 8 0 0 0 0-16zm0 9a1 1 0 0 1-1-1V8a1 1 0 0 1 2 0v4a1 1 0 0 1-1 1zm0 4a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Inventory Overview Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                                    {{ __('Inventory Overview') }}
                                </h3>
                                <div class="mt-2 space-y-2">
                                    <div class="flex items-center">
                                        <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalProducts }}</span>
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Total Products</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalCategories }}</span>
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Categories</span>
                                    </div>
                                </div>
                                <a href="{{ route('products.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-500 border border-transparent rounded-md text-xs text-white uppercase tracking-widest hover:bg-blue-700 dark:hover:bg-blue-400 transition">
                                    {{ __('Manage Products') }}
                                </a>
                            </div>
                            <svg class="h-12 w-12 text-blue-200 dark:text-blue-800" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 3H4a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1zM8 17H6v-2h2v2zm0-4H6v-2h2v2zm0-4H6V7h2v2zm10 8h-8v-2h8v2zm0-4h-8v-2h8v2zm0-4h-8V7h8v2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Stock Value Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-green-600 dark:text-green-400">
                                    {{ __('Stock Value') }}
                                </h3>
                                <div class="mt-2 space-y-2">
                                    <div class="flex items-center">
                                        <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">${{ number_format($totalStockValue, 2) }}</span>
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Total Value</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $featuredProducts }}</span>
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Featured Products</span>
                                    </div>
                                </div>
                            </div>
                            <svg class="h-12 w-12 text-green-200 dark:text-green-800" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sales Statistics -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Today's Sales -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-green-600 dark:text-green-400">
                                    {{ __('Today\'s Sales') }}
                                </h3>
                                <div class="mt-2 space-y-2">
                                    <div class="flex items-center">
                                        <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $todaySales }}</span>
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Transactions</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">৳{{ number_format($todayRevenue, 2) }}</span>
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Revenue</span>
                                    </div>
                                </div>
                            </div>
                            <svg class="h-12 w-12 text-green-200 dark:text-green-800" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Monthly Sales -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                                    {{ __('Monthly Sales') }}
                                </h3>
                                <div class="mt-2 space-y-2">
                                    <div class="flex items-center">
                                        <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $monthlySales }}</span>
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Transactions</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">৳{{ number_format($monthlyRevenue, 2) }}</span>
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Revenue</span>
                                    </div>
                                </div>
                            </div>
                            <svg class="h-12 w-12 text-blue-200 dark:text-blue-800" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z"/>
                                <path d="M7 12h2v5H7zm4-3h2v8h-2zm4-3h2v11h-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Sales -->
            @if($recentSales->count() > 0)
                <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ __('Recent Sales') }}
                            </h3>
                            <a href="{{ route('pos.sales') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View All Sales
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Sale Number
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Cashier
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Amount
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($recentSales as $sale)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $sale->sale_number }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $sale->user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $sale->formatted_total }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $sale->created_at->format('M d, H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('pos.show', $sale->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                                <a href="{{ route('pos.receipt', $sale->id) }}" target="_blank" class="text-green-600 hover:text-green-900">Receipt</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Quick Actions') }}
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <a href="{{ route('pos.index') }}" class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/40 transition">
                            <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m6 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"/>
                            </svg>
                            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-100">New Sale</span>
                        </a>
                        <a href="{{ route('products.create') }}" class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-100">Add Product</span>
                        </a>
                        <a href="{{ route('categories.create') }}" class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-100">Add Category</span>
                        </a>
                        <a href="{{ route('categories.index') }}" class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <svg class="h-6 w-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-100">Manage Categories</span>
                        </a>
                        <a href="{{ route('lowStockProducts') }}" class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-100">Check Low Stock</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
