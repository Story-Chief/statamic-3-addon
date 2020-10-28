<?php

namespace StoryChief\StoryChief\FieldHandlers;

use Statamic\Facades\User;

class UserFieldHandler extends BaseFieldHandler {

  /**
   * @inheritdoc
   *
   * @return string|null
   */
  public function getValue(): ?string {
    $user = User::findByEmail($this->payload_value);

    return $user ? $user->id() : NULL;
  }

}
