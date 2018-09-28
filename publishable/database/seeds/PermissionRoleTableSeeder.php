<?php

use Illuminate\Database\Seeder;
use SertxuDeveloper\Lyra\Models\Permission;
use SertxuDeveloper\Lyra\Models\Role;

class PermissionRoleTableSeeder extends Seeder {

  /**
   * Add the root permissions.
   *
   * @return void
   */
  public function run() {
    $role = Role::where('name', 'admin')->firstOrFail();

    $permissions = Permission::all();

    $role->permissions()->sync(
      $permissions->pluck('id')->all()
    );

  }
}