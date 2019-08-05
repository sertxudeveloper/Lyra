<?php

use Illuminate\Database\Seeder;
use SertxuDeveloper\Lyra\Models\MenuItem;

class MenuItemsTableSeeder extends Seeder {

  /**
   * Add the role admin and user.
   *
   * @return void
   */
  public function run() {

    $item = MenuItem::firstOrNew(['route' => 'lyra.dashboard']);
    if(!$item->exist) {
      $item->name = "Dashboard";
      $item->icon = "fas fa-home";
      $item->order = 1;
      $item->save();
    }

    $item = MenuItem::firstOrNew(['route' => 'lyra.media']);
    if(!$item->exist) {
      $item->name = "Media";
      $item->icon = "fas fa-images";
      $item->order = 2;
      $item->save();
    }

    $item = MenuItem::firstOrNew(['route' => 'lyra.widgets']);
    if(!$item->exist) {
      $item->name = "Widgets";
      $item->icon = "fas fa-tachometer-alt";
      $item->order = 3;
      $item->save();
    }

    $item = MenuItem::firstOrNew(['route' => 'lyra.users']);
    if(!$item->exist) {
      $item->name = "Users";
      $item->icon = "fas fa-users";
      $item->order = 4;
      $item->save();
    }

    $item = MenuItem::firstOrNew(['route' => 'lyra.roles']);
    if(!$item->exist) {
      $item->name = "Roles";
      $item->icon = "fas fa-lock";
      $item->order = 5;
      $item->save();
    }

    $item = MenuItem::firstOrNew(['route' => 'lyra.menu']);
    if(!$item->exist) {
      $item->name = "Menu";
      $item->icon = "fas fa-list";
      $item->order = 6;
      $item->save();
    }

    $item = MenuItem::firstOrNew(['route' => 'lyra.settings']);
    if(!$item->exist) {
      $item->name = "Settings";
      $item->icon = "fas fa-cog";
      $item->order = 7;
      $item->save();
    }

    $item = MenuItem::firstOrNew(['route' => 'lyra.crud']);
    if(!$item->exist) {
      $item->name = "CRUD";
      $item->icon = "fas fa-database";
      $item->order = 8;
      $item->save();
    }
  }
}
