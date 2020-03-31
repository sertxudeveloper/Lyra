<?php

namespace SertxuDeveloper\Lyra\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model {

  protected $table = 'lyra_permissions';
  protected $guarded = [];
  public $incrementing = false;

}
