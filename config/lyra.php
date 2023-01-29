<?php

return [
    /*
     |------------------------------------------------------------
     | Lyra routes configuration
     |------------------------------------------------------------
     |
     | You can customize the routes used by Lyra.
     |
     */
    //    "routes" => [
    //        "web" => [
    //            "prefix" => "lyra",
    //            "name" => "lyra.",
    //        ],
    //        "api" => [
    //            "prefix" => "lyra-api",
    //            "name" => "lyra-api."
    //        ]
    //    ],

    /*
    |--------------------------------------------------------------------------
    | Lyra Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where Lyra will be accessible from. Feel free
    | to change this path to anything you like. Note that the URI will not
    | affect the paths of its internal API that aren't exposed to users.
    |
    */
    'path' => 'lyra',

    /*
    |--------------------------------------------------------------------------
    | Lyra Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will get attached onto each Lyra route, giving you
    | the chance to add your own middleware to this list or change any of
    | the existing middleware. Or, you can simply stick with this list.
    |
    */
    'middleware' => ['web'],

    /*
     | -----------------------------------------------------------
     | Authentication provider
     | -----------------------------------------------------------
     |
     | You can use any of the following providers:
     | - default: Use the default Laravel authentication provider
     | - lyra: Use the Lyra authentication provider
     |
     */
    'auth' => 'default',

    /*
     |------------------------------------------------------------
     | Authorized Email addresses
     |------------------------------------------------------------
     |
     | If you selected the default authentication provider,
     | you must specify authorized email addresses.
     |
     | Only authorized email addresses will be able to
     | access the Lyra functionality.
     |
     */
    'authorized_emails' => [
        //
    ],

    /*
     |------------------------------------------------------------
     | Lyra logo URL
     |------------------------------------------------------------
     |
     | You can specify a logo URL to be displayed in the
     | Lyra dashboard instead of the default logo.
     |
     */
    'logo_url' => null,
];
