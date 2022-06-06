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
    "routes" => [
        "web" => [
            "prefix" => "lyra",
            "name" => "lyra.",
        ],
        "api" => [
            "prefix" => "lyra-api",
            "name" => "lyra-api."
        ]
    ],

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
    "auth" => "default",

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
    "authorized_emails" => [
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
    "logo_url" => null,
];
