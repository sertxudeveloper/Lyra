<?php

use Illuminate\Database\Seeder;
use SertxuDeveloper\Lyra\Models\Permission;

class PermissionsTableSeeder extends Seeder {

  /**
   * Add the root permissions.
   *
   * @return void
   */
  public function run() {

    $permission = Permission::firstOrNew(["key" => "read_lyra"]);
    if (!$permission->exist) $permission->save();

    $permission = Permission::firstOrNew(["key" => "read_media"]);
    if (!$permission->exist) $permission->save();

    $permission = Permission::firstOrNew(["key" => "read_widgets"]);
    if (!$permission->exist) $permission->save();

    $permission = Permission::firstOrNew(["key" => "read_users"]);
    if (!$permission->exist) $permission->save();

    $permission = Permission::firstOrNew(["key" => "read_roles"]);
    if (!$permission->exist) $permission->save();

    $permission = Permission::firstOrNew(["key" => "read_menu"]);
    if (!$permission->exist) $permission->save();

    $permission = Permission::firstOrNew(["key" => "read_settings"]);
    if (!$permission->exist) $permission->save();

    $permission = Permission::firstOrNew(["key" => "read_crud"]);
    if (!$permission->exist) $permission->save();
  }
}