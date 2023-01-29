<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class AssetsController extends Controller
{
    /**
     * Get the requested asset from the internal vendor folder.
     *
     * @param  Request  $request
     * @param $path string|null Asset path
     * @return Response
     */
    public function __invoke(Request $request, string $path = null): Response {
        abort_if(!$path, Response::HTTP_NOT_FOUND);

        $file = dirname(__DIR__, 3).'/public/'.$path;

        abort_if(!file_exists($file), Response::HTTP_NOT_FOUND);

        $mime = match (Str::afterLast($path, '.')) {
            'css' => 'text/css',
            'js' => 'application/javascript',
            default => mime_content_type($path),
        };

        return response(file_get_contents($file), 200, [
            'Content-Type' => $mime,
        ]);
    }
}
