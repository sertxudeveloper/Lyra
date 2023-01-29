<?php

namespace SertxuDeveloper\Lyra\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public array $items = [];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct() {
        dd('__construct');
        $this->items = $this->getSidebarItems();
    }

    /**
     * Render the component
     *
     * @return View
     */
    public function render(): View {
        dd('render');
        dd($this->items);
        return view('lyra::components.sidebar');
    }

    /**
     * Get the sidebar items
     *
     * @return array
     */
    protected function getSidebarItems(): array {
        dd('getSidebarItems');
        return [];
    }
}
