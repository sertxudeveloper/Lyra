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

    public function show(Request $request, $any = null): Response {
        abort_if(!$any, Response::HTTP_NOT_FOUND);

        $file = Lyra::asset($any);
        abort_if(!file_exists($file), Response::HTTP_NOT_FOUND);

        if (Str::afterLast($file, '.') == 'css') {
            $mime = 'text/css';
        }

        if (Str::afterLast($file, '.') == 'js') {
            $mime = 'application/javascript';
        }

        return response(file_get_contents($file), 200, ['Content-Type' => $mime ?? mime_content_type($file)]);
    }
}
