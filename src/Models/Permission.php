<?php

namespace SertxuDeveloper\Lyra\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model {

  use SoftDeletes;
  protected $guarded = [];
  protected $appends = ['menu_key'];

}