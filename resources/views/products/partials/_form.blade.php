@php
    $product = $product ?? null;
    $subcategories = $subcategories ?? [];
@endphp

<form method="POST" action="{{ $product ? route('products.update', $product) : route('products.store') }}" enctype="multipart/form-data">
    @csrf
    @if($product) @method('PUT') @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Subcategory Selection -->
        <div>
            <x-input-label for="subcategory_id" :value="__('Subcategory')" />
            <select id="subcategory_id"
                    name="subcategory_id"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">Select a subcategory</option>
                @foreach($subcategories as $subcategory)
                    <option value="{{ $subcategory->id }}"
                            {{ old('subcategory_id', $product?->sub_category_id) == $subcategory->id ? 'selected' : '' }}>
                        {{ $subcategory->category->name }} - {{ $subcategory->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('subcategory_id')" class="mt-2" />
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $product?->name)" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Description -->
        <div class="col-span-2">
            <x-input-label for="description" :value="__('Description')" />
            <textarea id="description"
                      name="description"
                      rows="4"
                      class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', $product?->description) }}</textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>

        <!-- SKU -->
        <div>
            <x-input-label for="sku" :value="__('SKU')" />
            <x-text-input type="text"
                         name="sku"
                         id="sku"
                         class="mt-1 block w-full"
                         :value="old('sku', $product?->sku)"
                         required />
            <x-input-error :messages="$errors->get('sku')" class="mt-2" />
        </div>

        <!-- Image -->
        <div>
            <x-input-label for="image" :value="__('Image')" />
            <input type="file"
                   id="image"
                   name="image"
                   accept="image/*"
                   class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-md file:border-0
                          file:text-sm file:font-semibold
                          file:bg-blue-50 file:text-blue-700
                          dark:file:bg-blue-900 dark:file:text-blue-200
                          hover:file:bg-blue-100 dark:hover:file:bg-blue-800" />
            <x-input-error :messages="$errors->get('image')" class="mt-2" />

            @if($product && $product->image)
                <div class="mt-2">
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="h-20 w-20 object-cover rounded">
                </div>
            @endif
        </div>

        <!-- Status Toggles -->
        <div class="col-span-2 space-y-4">
            <!-- Active Status -->
            <label class="inline-flex items-center">
                <input type="checkbox"
                       name="is_active"
                       class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                       value="1"
                       {{ old('is_active', $product?->is_active) ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Active') }}</span>
            </label>

            <!-- Featured Status -->
            <label class="inline-flex items-center">
                <input type="checkbox"
                       name="is_featured"
                       class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                       value="1"
                       {{ old('is_featured', $product?->is_featured) ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Featured') }}</span>
            </label>
        </div>
    </div>

    <div class="flex items-center justify-end mt-6">
        <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            {{ __('Cancel') }}
        </a>
        <x-primary-button class="ml-4">
            {{ $product ? __('Update Product') : __('Create Product') }}
        </x-primary-button>
    </div>
</form>
