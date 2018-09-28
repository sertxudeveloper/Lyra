<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionRoleTable extends Migration {

  /**
   * Run the migration.
   *
   * @return void
   */
  public function up() {
    Schema::create('permission_role', function (Blueprint $table) {
      $table->integer('role_id')->unsigned();
      $table->integer('permission_id')->unsigned();

      $table->primary(['role_id', 'permission_id']);
    });
  }

  /**
   * Reverse the migration.
   *
   * @return void
   */
  public function down() {
    Schema::drop('permission_role');
  }
}