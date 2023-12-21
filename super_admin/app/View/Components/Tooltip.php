<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Tooltip extends Component
{
    // Example usage: <p>Do the thing.<x-tooltip hover-text="Click for help" href="#">?</x-tooltip></p>
    
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public string $hoverText,
        public string $href,
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.tooltip');
    }
}
