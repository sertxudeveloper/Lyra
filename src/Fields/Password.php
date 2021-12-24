<?php

namespace SertxuDeveloper\Lyra\Fields;

class Password extends Field {

  public string $component = 'field-password';

  public bool $showOnIndex = false;
  public bool $showOnShow = false;
  public bool $showOnCreate = true;
  public bool $showOnUpdate = true;
}
