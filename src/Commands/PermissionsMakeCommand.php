<?php

namespace SertxuDeveloper\Lyra\Commands;

use Illuminate\Console\Command;
use SertxuDeveloper\Lyra\Models\Permission;
use SertxuDeveloper\Lyra\Models\Role;

class PermissionsMakeCommand extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'lyra:permission {role}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Set permissions to a Lyra role';

  public function handle() {
    $role_id = $this->getRoleId();
    $resource = $this->askResource();

    switch ($resource) {
      case 'media':
        $actions = $this->askActionsMedia();
        break;

      default:
        $actions = $this->askActions();
        break;
    }

    $this->info('Saving permissions');
    foreach ($actions as $action => $value) {
      if ($value) {
        Permission::updateOrCreate([
          'role_id' => $role_id,
          'resource_key' => $resource,
          'action' => $action
        ]);
      } else {
        $permission = Permission::where([
          'role_id' => $role_id,
          'resource_key' => $resource,
          'action' => $action
        ])->first();

        if ($permission) $permission->delete();
      }
    }

    return $this->handle();
  }

  private function getRoleId() {
    $role_name = $this->argument('role');
    $role = Role::where('name', $role_name)->first();
    if (!$role) {
      $this->error('Role not found!');
      exit(1);
    }
    return $role->id;
  }

  private function askResource() {
    $options = [];
    foreach (config('lyra.menu') as $item) {
      if (isset($item['hidden']) && $item['hidden'] === true) continue;
      if ($item['key'] === 'lyra') continue;
      $options[] = $item['key'];
    }
    $selected = $this->choice('Select a resource (Press "Ctrl + C" to exit)', $options);
    return $selected;
  }

  private function askActions() {
    $actions = [];
    $actions['read'] = $this->confirm('Will be able to read?');
    $actions['write'] = $this->confirm('Will be able to write?');
    $actions['delete'] = $this->confirm('Will be able to delete?');
    return $actions;
  }

  private function askActionsMedia() {
    $actions = [];
    $actions['read'] = $this->confirm('Will be able to access?');
    return $actions;
  }
}
