<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use SertxuDeveloper\Lyra\Http\Controllers\TranslatorController;
use SertxuDeveloper\Lyra\Lyra;

class MorphOneToOne extends Field {

  protected $component = "morph-one-to-one-field";
  protected $hideOnIndex = true;

  /**
   * Delete the file
   * The file will be deleted only if the prunable option is enabled
   *
   * @param $model
   */
  public function delete($model) {
    $this->removeMorphed($model);
  }

  /**
   * Get the value of the Field
   *
   * @param $model
   * @param string $type Can be 'index', 'edit', 'show' or 'create'
   * @return array
   */
  public function getValue($model, string $type): array {
    $value = null;

    if ($type === 'create') {
      $value = [];
    } else if ($type === 'edit') {
      $value = $this->getOriginalValueEdit($model);
    } else if ($type === 'show') {
      $value = $this->getOriginalValueShow($model);
    }

    $this->data->put('value', $value);

    return $this->data->toArray();
  }

  /**
   * Overwrite hideOnIndex visibility
   *
   * @param bool $hide
   * @return static
   */
  public function hideOnIndex(bool $hide = true) {
    $this->hideOnIndex = true;
    return $this;
  }

  /**
   * Save the $field value in the model
   *
   * @param array $field
   * @param $model
   */
  public function saveValue(array $field, $model): void {
    $morphedModel = $model->{$this->data->get('column')};

    /** If the resource type has changed, remove the old type from the database and all the translations if required */
    $removed = $this->removeMorphed($field, $model);

    if (!$morphedModel || $removed) {
      $morphedModel = Lyra::getResources()[$field['resource']]::$model;
      $morphedModel = new $morphedModel;
    }

    foreach ($field['value'] as $item) {
      if (TranslatorController::isTranslatorUsable() && isset($item['translatable']) && $item['translatable'] === true && !$removed) {
        TranslatorController::updateTranslation($item, $morphedModel);
      } else {
        $morphedModel->{$item['column']} = $item['value'];
      }
    }

    $morphedModel->save();
    $model->{$this->data->get('column')}()->associate($morphedModel);
  }

  public function types(array $types) {
    $resources = Lyra::getResources();
    $availableTypes = [];
    $this->data->put('resource', "");

    foreach ($types as $type) {
      $search = array_search($type, $resources);
      if ($search) {
        $permission = Lyra::checkPermission('write', $search);
        if (!$permission) continue;
        $availableTypes[] = ["key" => $search, "value" => Arr::last(explode('\\', $type))];
      }
    }

    $this->data->put('types', $availableTypes);
    return $this;
  }

  /**
   * @param $model
   * @return mixed
   */
  private function getOriginalValueEdit($model) {
    $morphedModel = $model->{$this->data->get('column')};

    $resource = $this->setResource($model);
    $resourceCollection = new $resource(collect([$morphedModel]));

    $resourceCollection->singular = Str::singular($this->data->get('name'));
    $resourceCollection->plural = Str::plural($this->data->get('name'));

    $this->data->put('id', $morphedModel->id);
    return $resourceCollection->getCollection(request(), 'edit')['collection']['data'][0];
  }

  /**
   * @param $model
   * @return mixed
   */
  private function getOriginalValueShow($model) {
    $morphedModel = $model->{$this->data->get('column')};

    $resource = $this->setResource($model);
    $resourceCollection = new $resource(collect([$morphedModel]));

    $resourceCollection->singular = Str::singular($this->data->get('name'));
    $resourceCollection->plural = Str::plural($this->data->get('name'));

    return $resourceCollection->getCollection(request(), 'index')['collection']['data'][0];
  }

  /**
   * @param $model
   * @return bool
   */
  private function removeMorphed($model) {
    $resource = $this->setResource($model);

    $search = array_search($this->setResource($model), Lyra::getResources());

    if ($resource !== $search) {
      $deleteModel = Lyra::getResources()[$search]::$model;
      $primaryColumn = (new $deleteModel)->getKeyName();
      $deleteModel = $deleteModel::where($primaryColumn, $model[$primaryColumn])->first();

      if (TranslatorController::isTranslatorUsable()) {
        TranslatorController::removeTranslations($deleteModel);
      }

      $deleteModel->delete();
      return true;
    }

    return false;
  }

  /**
   * @param $model
   * @return |null
   */
  private function setResource($model) {
    $resources = Lyra::getResources();
    $type = get_class($model->{$this->data->get('column')});

    foreach ($resources as $resource) {
      if ($resource::$model === $type) {
        $this->data->put('resource', array_search($resource, $resources));
        return $resource;
      }
    }

    return null;
  }
}
