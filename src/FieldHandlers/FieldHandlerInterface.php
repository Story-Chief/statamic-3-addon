<?php

namespace StoryChief\StoryChief\FieldHandlers;

use Statamic\Entries\Entry;
use Statamic\Fields\Field;

interface FieldHandlerInterface {

  /**
   * BaseFieldHandler constructor.
   *
   * @param \Statamic\Entries\Entry $entry
   * @param \Statamic\Fields\Field $field
   * @param array $payload
   * @param $payload_value
   */
  public function __construct(Entry $entry, Field $field, array $payload, $payload_value);

  /**
   * @return mixed
   */
  public function getValue();

  /**
   * @return void
   */
  public function setField(): void;
}
