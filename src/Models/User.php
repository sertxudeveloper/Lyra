<?php

namespace SertxuDeveloper\Lyra\Models;

use Illuminate\Foundation\Auth\User as Authenticable;
use SertxuDeveloper\Lyra\Traits\Notifiable;

class User extends Authenticable {

  use Notifiable;
  protected $table = 'lyra_users';
  protected $guarded = [];

  /**
   * Check if the user has the requested permission
   *
   * @param $action
   * @param $resource
   * @return bool
   */
  public function hasPermission($action, $resource): bool {
    if ($resource === 'lyra') return true;
//    if ($resource === 'posts') return true;
//    dd($resource, $action);
    return false;
//    return $this->role->permissions->contains("key", $permission);
  }

  /**
   * Get the role of the user
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function role() {
    return $this->belongsTo(Role::class);
  }
}
