<?php

namespace SertxuDeveloper\Lyra\Facades;

use Illuminate\Support\Facades\Facade;

class Lyra extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string {
        return 'lyra';
    }
}
