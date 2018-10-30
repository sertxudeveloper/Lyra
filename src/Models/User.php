<?php

namespace SertxuDeveloper\Lyra\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {

  use Notifiable;

  protected $table = 'lyra_users';

  protected $guarded = [];

  /**
   * Get the role of the user
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function role() {
    return $this->belongsTo(Role::class);
  }

  /**
   * Check if the user has the requested permission
   *
   * @param $permission string Key of the requested permission
   * @return bool
   */
  public function hasPermission($permission): bool {
    return $this->role->permissions->contains("key", $permission);
  }
}