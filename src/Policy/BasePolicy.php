<?php

namespace SertxuDeveloper\Lyra\Policy;

use Illuminate\Auth\Access\HandlesAuthorization;

class BasePolicy {
  use HandlesAuthorization;

  /**
   * Handle all requested permission checks.
   *
   * @param string $name
   * @param array $arguments
   *
   * @return bool
   */
  public function __call($name, $arguments) {

    if (count($arguments) < 2) {
      throw new \InvalidArgumentException('not enough arguments');
    }

    $user = $arguments[0];
    $model = $arguments[1];

    return $this->checkPermission($user, $model, $name);
  }

//
//  /**
//   * Check if user has an associated permission.
//   *
//   * @param \SertxuDeveloper\Voyager\Contracts\User $user
//   * @param object $model
//   * @param string $action
//   *
//   * @return bool
//   */
//  protected function checkPermission(User $user, $model, $action) {
//    if (!isset(self::$datatypes[get_class($model)])) {
//      $dataType = Voyager::model('Datatype');
//      self::$datatypes[get_class($model)] = $dataType->where('model_name', get_class($model))->first();
//    }
//
//    $dataType = self::$datatypes[get_class($model)];
//
//    return $user->hasPermission($action . '_' . $dataType->name);
//  }
}