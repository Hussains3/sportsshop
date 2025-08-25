<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ $category->name }}
            </h2>
            <div>
                <a href="{{ route('categories.edit', $category) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Edit Category
                </a>
                <a href="{{ route('categories.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Category Details</h3>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    @if ($category->description)
                    <div class="mb-4">
                        <p class="mt-1"><strong class="text-gray-700 dark:text-gray-300">Description: </strong>{{ $category->description }}</p>
                    </div>
                    @endif



                    <!-- Subcategories Section -->
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Subcategories</h3>
                            <a href="{{ route('subcategories.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-2 px-4 rounded">
                                Add Subcategory
                            </a>
                        </div>

                        @if($subcategories->count() > 0)
                            <div class="bg-white dark:bg-gray-700 shadow overflow-hidden sm:rounded-md">
                                <div class="divide-y divide-gray-200 dark:divide-gray-600 grid grid-cols-4">
                                    @foreach($subcategories as $subcategory)
                                    <a href="{{ route('subcategories.show', $subcategory) }}" class="">
                                        <div class="px-4 py-4 sm:px-6 hover:bg-gray-50 dark:hover:bg-gray-600 transition duration-150 ease-in-out">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $subcategory->name }}
                                                    </div>
                                                    <div class="ml-4 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                                        {{ $subcategory->products_count }} {{ Str::plural('Product', $subcategory->products_count) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">No subcategories found for this category.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
