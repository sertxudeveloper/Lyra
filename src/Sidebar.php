<?php

namespace SertxuDeveloper\Lyra;

class Sidebar
{
    /**
     * Get sidebar resource elements
     *
     * @return array
     */
    public static function items(): array {
        $items = collect();

        foreach (Lyra::getResources() as $class) {
            $items->push([
                'name' => $class::label(),
                'slug' => $class::slug(),
                'icon' => $class::$icon,
                'priority' => $class::$priority,
            ]);
        }

        return $items->sortBy('priority')->all();
    }
}
