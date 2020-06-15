<?php

namespace SertxuDeveloper\Lyra\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use SertxuDeveloper\Lyra\Lyra;
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
    if (config('lyra.authenticator') !== Lyra::MODE_ADVANCED) {
      $this->error('This command is only available using the Advanced mode!');
      exit(1);
    }

    $options = $this->mapOptions(config('lyra.menu'));
    $resource = $this->askResource($options);

    if ($resource === '[All]') {
      return $this->grantAll($options);
    } else if ($options[$resource] === 'component') {
      $actions = $this->askActionsComponent();
    } else {
      $actions = $this->askActionsResource();
    }

    $this->grantResource($resource, $actions);
    $this->info('New permissions saved!');

    return $this->handle();
  }

  private function askActionsResource() {
    $actions = [];
    $actions['read'] = $this->confirm('Will be able to read?');
    $actions['write'] = $this->confirm('Will be able to write?');
    $actions['delete'] = $this->confirm('Will be able to delete?');
    return $actions;
  }

  private function askActionsComponent() {
    $actions = [];
    $actions['read'] = $this->confirm('Will be able to access?');
    return $actions;
  }

  private function askResource($options) {
    $selected = $this->choice('Select a resource or a component (Press "Ctrl + C" to exit)', array_keys($options));
    return $selected;
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

  private function grantAll($options) {
    $read = $this->confirm('Will be able to read?');
    $write = $this->confirm('Will be able to write?');
    $delete = $this->confirm('Will be able to delete?');

    foreach ($options as $resource => $type) {
      if ($type === 'component') {
        $actions = ['read' => $read];
      } else if ($type === 'resource') {
        $actions = ['read' => $read, 'write' => $write, 'delete' => $delete];
      } else {
        continue;
      }

      $this->grantResource($resource, $actions);
    }

    $this->info('New permissions applied to all the resources and components!');

    return $this->handle();
  }

  private function grantResource($resource, $actions) {
    $role_id = $this->getRoleId();

    foreach ($actions as $action => $value) {
      if ($value) {
        Permission::updateOrCreate([
          'role_id' => $role_id, 'resource_key' => $resource, 'action' => $action
        ]);
      } else {
        $permission = Permission::where([
          'role_id' => $role_id, 'resource_key' => $resource, 'action' => $action
        ])->first();

        if ($permission) $permission->delete();
      }
    }
  }

  private function mapOptions($items) {
    $options = ['[All]' => 'special'];

    foreach ($items as $item) {
      if (isset($item['key']) && $item['key'] === 'lyra') continue;

      if (isset($item['items'])) {
        $options = array_merge($options, $this->mapOptions($item['items']));
      } else {
        if (!isset($item['key'])) $item['key'] = Str::snake($item['name']);
        $options[$item['key']] = (isset($item['resource'])) ? 'resource' : 'component';
      }
    }

    return $options;
  }
}
