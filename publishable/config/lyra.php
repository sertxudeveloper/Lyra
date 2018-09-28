<?php

return [
  "routes" => [
    "prefix" => "lyra",
    "name" => "lyra."
  ],

  /*
  |--------------------------------------------------------------------------
  | Path to the Lyra Assets
  |--------------------------------------------------------------------------
  |
  | Here you can specify the location of the lyra assets path
  |
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
  | Title and description of the Lyra Panel
  |--------------------------------------------------------------------------
  */
  "menu" => [
    [
      "name" => "Dashboard",
      "route" => "lyra.dashboard",
      "icon" => "fas fa-home",
    ],
    [
      "name" => "Media",
      "route" => "lyra.media",
      "icon" => "fas fa-images",
    ],
    [
      "name" => "Widgets",
      "route" => "lyra.widget",
      "icon" => "fas fa-tachometer-alt",
    ],
//    [
//      "name" => "Users",
//      "route" => "lyra.users",
//      "icon" => "fas fa-users",
//    ],
    [
      "name" => "Roles",
      "route" => "lyra.roles",
      "icon" => "fas fa-lock",
    ],
    [
      "name" => "Menu",
      "route" => "lyra.menu",
      "icon" => "fas fa-list",
    ],
    [
      "name" => "Settings",
      "route" => "lyra.settings",
      "icon" => "fas fa-cog",
    ],
    [
      "name" => "CRUD",
      "route" => "lyra.crud",
      "icon" => "fas fa-database",
    ]
  ],

  "datatypes" => [
    "users" => [
      "singular_name" => "User",
      "plural_name" => "Users",
      "model" => "App\User",
    ]
  ]
];