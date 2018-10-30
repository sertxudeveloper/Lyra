<?php

return [
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
  |--------------------------------------------------------------------------
  | Path to the Lyra Assets
  |--------------------------------------------------------------------------
  */
  'assets_path' => '/vendor/sertxudeveloper/lyra/assets',

  /*
  |--------------------------------------------------------------------------
  | Title and description of the Lyra Panel
  |--------------------------------------------------------------------------
  */
  "admin" => [
    "title" => "Lyra Panel",
    "description" => "Bootstrap your app and turn ideas into reality"
  ],

  /*
  |--------------------------------------------------------------------------
  | Lyra menu
  |--------------------------------------------------------------------------
  */
  "menu" => [
    [
      "name" => "Dashboard",
      "key" => "lyra",
      "icon" => "fas fa-home",
    ],
    [
      "name" => "Media",
      "key" => "media",
      "icon" => "fas fa-images",
    ],
    [
      "name" => "Widgets",
      "key" => "widgets",
      "icon" => "fas fa-tachometer-alt",
    ],
    [
      "name" => "Users",
      "key" => "users",
      "icon" => "fas fa-users",
    ],
    [
      "name" => "Roles",
      "key" => "roles",
      "icon" => "fas fa-lock",
    ],
    [
      "name" => "Menu",
      "key" => "menu",
      "icon" => "fas fa-list",
    ],
    [
      "name" => "Settings",
      "key" => "settings",
      "icon" => "fas fa-cog",
    ],
    [
      "name" => "CRUD",
      "key" => "crud",
      "icon" => "fas fa-database",
    ]
  ],

  /*
  |--------------------------------------------------------------------------
  | Lyra widgets
  |--------------------------------------------------------------------------
  */
  "widgets" => [
    "row" => [
      "SertxuDeveloper\Lyra\Http\Widgets\UserWidget",
    ]
  ]
];