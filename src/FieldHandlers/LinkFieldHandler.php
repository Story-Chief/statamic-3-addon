<?php

namespace StoryChief\StoryChief\FieldHandlers;

class LinkFieldHandler extends BaseFieldHandler {

  /**
   * @inheritdoc
   *
   * @return string|null
   */
  public function getValue(): ?string {
    if (filter_var($this->payload_value, FILTER_VALIDATE_URL)) {
      return $this->payload_value;
    }

    return NULL;
  }

}
