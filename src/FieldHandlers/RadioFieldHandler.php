<?php

namespace StoryChief\StoryChief\FieldHandlers;

use Illuminate\Support\Arr;

class RadioFieldHandler extends BaseFieldHandler {

  /**
   * @inheritdoc
   *
   * @return string|null
   */
  public function getValue(): ?string {
    $value = is_array($this->payload_value) ? $this->payload_value[0] : $this->payload_value;
    $options = Arr::get($this->field->config(), 'options', []);

    return Arr::first(array_keys($options), function ($option) use ($value) {
      return $option === $value;
    }, NULL);
  }

}
