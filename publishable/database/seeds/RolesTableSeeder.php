<?php

use Illuminate\Database\Seeder;
use SertxuDeveloper\Lyra\Models\Role;

class RolesTableSeeder extends Seeder {

  /**
   * Add the role admin and user.
   *
   * @return void
   */
  public function run() {

    $role = Role::firstOrNew(['name' => 'admin']);
    if (!$role->exists) {
      $role->display_name = "Administrator";
      $role->save();
    }

    $role = Role::firstOrNew(['name' => 'user']);
    if (!$role->exists) {
      $role->display_name = "Normal Users";
      $role->save();
    }
  }
}