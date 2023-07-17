<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use SertxuDeveloper\Lyra\Lyra;

class CardsController extends Controller
{
    /**
     * Get the models from the given resource
     *
     *
     * @throws Exception
     */
    public function index(Request $request, string $resource): object {
        $class = Lyra::resourceBySlug($resource);
        $resource = new $class($class::newModel());
        $cards = collect();

        foreach ($resource->cards() as $class) {
            $cards->push($class->toArray($request));
        }

        return $cards;
    }

    /**
     * Get the given card instance data
     *
     * @return object|void
     *
     * @throws Exception
     */
    public function show(Request $request, string $resource, string $card) {
        $class = Lyra::resourceBySlug($resource);
        $resource = new $class($class::newModel());

        foreach ($resource->cards() as $class) {
            if ($class->slug() !== $card) {
                continue;
            }

            return $class->toArray($request);
        }

        abort(404);
    }
}
