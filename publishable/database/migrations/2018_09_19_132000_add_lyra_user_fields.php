<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLyraUserFields extends Migration {

  /**
   * Run the migration.
   *
   * @return void
   */
  public function up() {
    Schema::table('lyra_users', function (Blueprint $table) {

      if (!Schema::hasColumn('lyra_users', 'avatar')) {
        $table->string('avatar')->nullable()->after('email')->default('users/default.png');
      }

      if (!Schema::hasColumn('lyra_users', 'role_id')) {
        $table->integer('role_id')->nullable()->after('id');
      }

      if (!Schema::hasColumn('lyra_users', 'preferred_theme')) {
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
    if (Schema::hasColumn('lyra_users', 'avatar')) {
      Schema::table('lyra_users', function (Blueprint $table) {
        $table->dropColumn('avatar');
      });
    }

    if (Schema::hasColumn('lyra_users', 'role_id')) {
      Schema::table('lyra_users', function (Blueprint $table) {
        $table->dropColumn('role_id');
      });
    }

    if (Schema::hasColumn('lyra_users', 'preferred_theme')) {
      Schema::table('lyra_users', function (Blueprint $table) {
        $table->dropColumn('preferred_theme');
      });
    }
  }
}
