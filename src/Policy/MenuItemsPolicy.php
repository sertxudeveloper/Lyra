<?php

namespace SertxuDeveloper\Lyra\Policy;

use SertxuDeveloper\Lyra\Models\MenuItems;
use SertxuDeveloper\Lyra\Models\User;

class MenuItemsPolicy extends BasePolicy {

  /**
   * Chech if the user has permission to browse the $model
   * @param User $user
   * @param MenuItems $model
   * @param string $name
   * @return bool
   */
//  protected function checkPermission(User $user, MenuItems $model, string $name): bool {
//    $regex = str_replace('/', '\/', preg_quote(route('lyra.dashboard')));
//    $slug = preg_replace('/' . $regex . '/', '', $model->link(true));
//    $slug = str_replace('/', '', $slug);
//
//    $slug = ($slug == "") ? 'lyra' : $slug;
//
////    var_dump($slug);
////    dd($user, $model, $name);
////    return true;
////    dd("{$name}_{$slug}",auth()->user()->hasPermission("{$name}_{$slug}"));
//    return auth()->user()->hasPermission("{$name}_{$slug}");
//  }
}