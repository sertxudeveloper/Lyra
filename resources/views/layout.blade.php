<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lyra Admin - {{ config('app.name') }}</title>
</head>
<body>
<div id="lyra" v-cloak>
    <div class="mb-5">
        <div class="mt-4">
            <div class="col-2">
                <x-lyra::sidebar/>
            </div>

            <div class="col-10">
                {{--@if (! $assetsAreCurrent)
                    <div class="alert alert-warning">
                        The published Lyra assets are not up-to-date with the installed version. To update, run:<br/><code>php artisan lyra:publish</code>
                    </div>
                @endif--}}
                <router-view></router-view>
            </div>
        </div>
    </div>

</div>
<script>
    window.Lyra = {
        basePath: '/{{ config('lyra.path') }}/api/'
    }
</script>

@vite('resources/js/app.js', 'vendor/lyra/build')
</body>
</html>
