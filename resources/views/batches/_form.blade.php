@php
    $batch = $batch ?? null;
@endphp
<form action="{{ $batch ? route('batches.update', $batch) : route('batches.store') }}" method="POST">
    @csrf
    @if($batch)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        <div>
            <x-input-label for="product_id" :value="__('Product')" />
            <select id="product_id" name="product_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">Select a product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ (old('product_id', $batch?->product_id) == $product->id) ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('product_id')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="batch_number" :value="__('Batch Number')" />
            <x-text-input id="batch_number" name="batch_number" type="text" class="mt-1 block w-full" :value="old('batch_number', $batch?->batch_number)" required autofocus />
            <x-input-error :messages="$errors->get('batch_number')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="initial_quantity" :value="__('Quantity')" />
            <x-text-input id="initial_quantity" name="initial_quantity" type="number" class="mt-1 block w-full" :value="old('initial_quantity', $batch?->initial_quantity)" required min="0" />
            <x-input-error :messages="$errors->get('initial_quantity')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="manufacturing_date" :value="__('Manufacturing Date')" />
            <x-text-input id="manufacturing_date" name="manufacturing_date" type="date" class="mt-1 block w-full" :value="old('manufacturing_date', $batch?->manufacturing_date?->format('Y-m-d'))" required />
            <x-input-error :messages="$errors->get('manufacturing_date')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="expiry_date" :value="__('Expiry Date')" />
            <x-text-input id="expiry_date" name="expiry_date" type="date" class="mt-1 block w-full" :value="old('expiry_date', $batch?->expiry_date?->format('Y-m-d'))" />
            <x-input-error :messages="$errors->get('expiry_date')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="purchase_price" :value="__('Purchase Price')" />
            <x-text-input id="purchase_price" name="purchase_price" type="number" step="0.01" class="mt-1 block w-full" :value="old('purchase_price', $batch?->purchase_price)" required />
            <x-input-error :messages="$errors->get('purchase_price')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="selling_price" :value="__('Selling Price')" />
            <x-text-input id="selling_price" name="selling_price" type="number" step="0.01" class="mt-1 block w-full" :value="old('selling_price', $batch?->selling_price)" required />
            <x-input-error :messages="$errors->get('selling_price')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="status" :value="__('Status')" />
            <select id="status" name="status" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                @foreach(['active', 'expired', 'depleted'] as $status)
                    <option value="{{ $status }}" {{ (old('status', $batch?->status) == $status) ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ $batch ? __('Update Batch') : __('Create Batch') }}</x-primary-button>
            <a href="{{ route('batches.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('Cancel') }}
            </a>
        </div>
    </div>
</form>
