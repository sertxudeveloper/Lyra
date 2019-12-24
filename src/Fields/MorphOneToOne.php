<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use SertxuDeveloper\Lyra\Http\Controllers\TranslatorController;
use SertxuDeveloper\Lyra\Lyra;

class MorphOneToOne extends Field {

  protected $component = "morph-one-to-one-field";
  protected $hideOnIndex = true;

  public function getValue($model, $type) {
    $value = null;

    switch ($type) {
      case 'index':
        return null;
        break;

      case 'show':
        $value = $this->getValueShow($model);
        break;

      case 'edit':
        $value = $this->getValueEdit($model);
        break;

      case 'create':
        $value = $this->getValueCreate($model);
        break;
    }

//    $this->data->put('value', []);
    $this->data->put('value', $value);
    return $this->data->toArray();
  }

  public function getValueShow($model) {
    $morphedModel = $model->{$this->data->get('column')};

    $resource = $this->setResource($model);
    $resourceCollection = new $resource(collect([$morphedModel]));

    $resourceCollection->singular = Str::singular($this->data->get('name'));
    $resourceCollection->plural = Str::plural($this->data->get('name'));

    return $resourceCollection->getCollection(request(), 'index')['collection']['data'][0];
  }

  public function getValueEdit($model) {
    $morphedModel = $model->{$this->data->get('column')};

    $resource = $this->setResource($model);
    $resourceCollection = new $resource(collect([$morphedModel]));

    $resourceCollection->singular = Str::singular($this->data->get('name'));
    $resourceCollection->plural = Str::plural($this->data->get('name'));

    $this->data->put('id', $morphedModel->id);
    return $resourceCollection->getCollection(request(), 'edit')['collection']['data'][0];
  }

  public function saveValue($field, $model) {
    $morphedModel = $model->{$this->data->get('column')};

    /** If the resource type has changed, remove the old type from the database and all the translations if required */
    $removed = $this->removeOldMorphed($field, $model);

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
    return $model->{$this->data->get('column')}()->associate($morphedModel);
  }

  public function types(array $types) {
    $resources = Lyra::getResources();
    $availableTypes = [];
    $this->data->put('resource', "");

    foreach ($types as $type) {
      $search = array_search($type, $resources);
      if ($search) {
        $availableTypes[] = ["key" => $search, "value" => Arr::last(explode('\\', $type))];
      }
    }

    $this->data->put('types', $availableTypes);
    return $this;
  }

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

  private function getValueCreate($model) {
    return [];
  }

  public function hideOnIndex(bool $hide = true) {
    return true;
  }

  private function removeOldMorphed($field, $model) {
    $search = array_search($this->setResource($model), Lyra::getResources());
    if (!empty($field['resource']) && $field['resource'] !== $search) {
      $deleteModel = Lyra::getResources()[$search]::$model;
      $primaryColumn = (new $deleteModel)->getKeyName();
      $deleteModel = $deleteModel::where($primaryColumn, $field[$primaryColumn])->first();

      if (TranslatorController::isTranslatorUsable()) {
        TranslatorController::removeTranslations($deleteModel);
      }

      $deleteModel->delete();
      return true;
    }

    return false;
  }
}
