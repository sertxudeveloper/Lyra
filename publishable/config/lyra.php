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
  | Specify the authenticator driver to use in the login
  |
  | If you select the basic driver, the user will be authenticated using the default Laravel authenticator driver.
  | With this driver, all the user will be blocked except those ones specified in the config file.
  | Besides the notifications functionality should be added manually to the User model.
  |
  | If you select the Lyra driver, the user will be authenticated using the Lyra provider and the Lyra guard.
  | With this driver, only the user registered in Lyra will be able to log in and access.
  | Besides the Lyra driver require its own user table in the database, so it won't work until you run the migration.
  | In addition, the notifications functionality will require another table to work.
  |
  | Supported: "basic", "lyra"
  |--------------------------------------------------------------------------
  */
  'authenticator' => 'basic',

  /*
   * Enable or disable the notificator system
   */
  'notificator' => false,

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
    /*[
      "name" => "Media",
      "key" => "media",
      "icon" => "fas fa-images",
    ],
    [
      "name" => "Widgets",
      "key" => "widgets",
      "icon" => "fas fa-tachometer-alt",
    ],*/
    [
      "name" => "Users", // The name is going to appear in the left side menu
      "key" => "users", // This key should be the same in the Lyra::resources() array
      "icon" => "fas fa-users", // Icon classes
      "resource" => SertxuDeveloper\Lyra\Http\Resources\User::class
    ],
    [
      "name" => "Lyra Users", // The name is going to appear in the left side menu
      "key" => "lyra-users", // This key should be the same in the Lyra::resources() array
      "icon" => "fas fa-user", // Icon classes
      "resource" => SertxuDeveloper\Lyra\Http\Resources\LyraUser::class
    ],
    [
      "name" => "Lyra Roles", // The name is going to appear in the left side menu
      "key" => "lyra-roles", // This key should be the same in the Lyra::resources() array
      "icon" => "fas fa-users-cog", // Icon classes
      "resource" => SertxuDeveloper\Lyra\Http\Resources\LyraRole::class
    ],
    /*[
      "name" => "Lyra",
      "key" => "lyra-settings",
      "icon" => "fas fa-cogs",
      "items" => [
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
      ]
    ]*/
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
