<?php

namespace SertxuDeveloper\Lyra\Tests\Feature\Http\Controllers;

use SertxuDeveloper\Lyra\Tests\TestCase;

class MainControllerTest extends TestCase
{
    public function test_can_load_dashboard() {
        $this->get(route('lyra.index'))
            ->assertOk()
            ->assertViewIs('lyra::layout');
    }
}
