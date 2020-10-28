<?php

namespace StoryChief\StoryChief\FieldHandlers;

class TermsFieldHandler extends BaseFieldHandler {

  /**
   * @inheritdoc
   *
   * @return array
   */
  public function getValue(): array {
    if (is_string($this->payload_value)) {
      return explode(',', $this->payload_value);
    }

    return is_array($this->payload_value) ? $this->payload_value : [];
  }

}
