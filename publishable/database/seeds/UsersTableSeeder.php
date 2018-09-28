<?php

use Illuminate\Database\Seeder;
use SertxuDeveloper\Lyra\Models\Role;
use SertxuDeveloper\Lyra\Models\User;

class UsersTableSeeder extends Seeder {

  /**
   * Add the default Admin user.
   *
   * @return void
   */
  public function run() {

    $role = Role::where('name', 'admin')->firstOrFail();
    $user = User::firstOrNew(['email' => 'admin@example.com']);

    if (!$user->exists) {
      $user->role_id = $role->id;
      $user->name = "Administrator";
      $user->email = "admin@example.com";
      $user->password = bcrypt('secret');
      $user->save();
    }
  }
}
