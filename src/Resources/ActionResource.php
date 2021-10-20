<?php

namespace SertxuDeveloper\Lyra\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActionResource extends JsonResource {

  public function toArray($request): array {
    return [
      'key' => $this->resource::slug(),
      'name' => $this->resource::label()
    ];
  }
}
