@props(['type' => 'success', 'message' => null])

@php
    $classes = match($type) {
        'success' => 'bg-green-100 dark:bg-green-600 border-green-400 text-green-700 dark:text-green-100',
        'error' => 'bg-red-100 dark:bg-red-600 border-red-400 text-red-700 dark:text-red-100',
        'warning' => 'bg-yellow-100 dark:bg-yellow-600 border-yellow-400 text-yellow-700 dark:text-yellow-100',
        'info' => 'bg-blue-100 dark:bg-blue-600 border-blue-400 text-blue-700 dark:text-blue-100',
        default => 'bg-gray-100 dark:bg-gray-600 border-gray-400 text-gray-700 dark:text-gray-100'
    };
@endphp

@if($message)
    <div class="fixed top-4 right-4 z-50 w-full max-w-sm animate-fade-in-down">
        <div id="success-alert" {{ $attributes->merge(['class' => "{$classes} border px-4 py-3 rounded-lg shadow-lg transition-all duration-300 transform opacity-100 flex items-center"]) }} role="alert">
            <div class="flex-grow">
                <span class="block sm:inline">{{ $message }}</span>
            </div>
            <button type="button" class="ml-4 text-lg font-semibold opacity-50 hover:opacity-75" onclick="this.parentElement.remove()">×</button>
        </div>
    </div>

    <style>
        @keyframes fade-in-down {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-down {
            animation: fade-in-down 0.3s ease-out;
        }
    </style>
@endif
