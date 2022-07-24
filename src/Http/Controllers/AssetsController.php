<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use SertxuDeveloper\Lyra\Lyra;

class AssetsController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Get the requested asset from the internal vendor folder.
     *
     * @param  Request  $request
     * @param $any string|null Asset path
     * @return Response
     */
    public function show(Request $request, string $any = null): Response {
        abort_if(!$any, Response::HTTP_NOT_FOUND);

        $file = Lyra::asset($any);
        abort_if(!file_exists($file), Response::HTTP_NOT_FOUND);

        $mime = match (Str::afterLast($file, '.')) {
            'css' => 'text/css',
            'js' => 'application/javascript',
            default => mime_content_type($file),
        };

        return response(file_get_contents($file), 200, [
            'Content-Type' => $mime,
        ]);
    }
}
