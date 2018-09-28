<?php

use Illuminate\Database\Migrations\Migration;

class AddLyraUserFields extends Migration {

  /**
   * Run the migration.
   *
   * @return void
   */
  public function up() {
    Schema::table('users', function ($table) {

      if (!Schema::hasColumn('users', 'avatar')) {
        $table->string('avatar')->nullable()->after('email')->default('users/default.png');
      }

      if (!Schema::hasColumn('users', 'role_id')) {
        $table->integer('role_id')->nullable()->after('id');
      }

      if (!Schema::hasColumn('users', 'preferred_theme')) {
        $table->string('preferred_theme', 10)->nullable()->after('avatar')->default('default');
        // OPTIONS
        //   - default
        //   - light
        //   - dark
      }
    });
  }

  /**
   * Reverse the migration.
   *
   * @return void
   */
  public function down() {
    if (Schema::hasColumn('users', 'avatar')) {
      Schema::table('users', function ($table) {
        $table->dropColumn('avatar');
      });
    }

    if (Schema::hasColumn('users', 'role_id')) {
      Schema::table('users', function ($table) {
        $table->dropColumn('role_id');
      });
    }

    if (Schema::hasColumn('users', 'preferred_theme')) {
      Schema::table('users', function ($table) {
        $table->dropColumn('preferred_theme');
      });
    }
  }
}