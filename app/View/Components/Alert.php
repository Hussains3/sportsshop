<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\View\View;

class Alert extends Component
{
    public function __construct(
        public string $type = 'success',
        public ?string $message = null
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.alert');
    }
}
