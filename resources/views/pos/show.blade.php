<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Sale Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Sale Header -->
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $sale->sale_number }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">{{ $sale->formatted_date }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('pos.receipt', $sale->id) }}"
                               target="_blank"
                               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                Print Receipt
                            </a>
                            <a href="{{ route('pos.sales') }}"
                               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                                Back to Sales
                            </a>
                        </div>
                    </div>

                    <!-- Sale Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Sale Information</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($sale->status === 'completed') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                        @elseif($sale->status === 'pending') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                        @else bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 @endif">
                                        {{ ucfirst($sale->status) }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Cashier:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $sale->user->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Payment Method:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Payment Summary</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">৳{{ number_format($sale->subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Tax (5%):</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">৳{{ number_format($sale->tax_amount, 2) }}</span>
                                </div>
                                @if($sale->discount_amount > 0)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Discount:</span>
                                        <span class="font-medium text-gray-900 dark:text-gray-100">-৳{{ number_format($sale->discount_amount, 2) }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between text-lg font-semibold border-t border-gray-200 dark:border-gray-600 pt-2">
                                    <span class="text-gray-900 dark:text-gray-100">Total:</span>
                                    <span class="text-gray-900 dark:text-gray-100">৳{{ number_format($sale->total_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Amount Paid:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">৳{{ number_format($sale->amount_paid, 2) }}</span>
                                </div>
                                @if($sale->change_amount > 0)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Change:</span>
                                        <span class="font-medium text-gray-900 dark:text-gray-100">৳{{ number_format($sale->change_amount, 2) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Sale Items -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Items Sold</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead class="bg-white dark:bg-gray-800">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Product
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Batch
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Quantity
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Unit Price
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Discount
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Subtotal
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach($sale->items as $item)
                                        <tr>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item->product->name }}</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item->product->sku }}</div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $item->batch->batch_number }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                ৳{{ number_format($item->unit_price, 2) }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                ৳{{ number_format($item->discount_amount, 2) }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                ৳{{ number_format($item->subtotal, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($sale->notes)
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Notes</h4>
                            <p class="text-gray-700 dark:text-gray-300">{{ $sale->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
