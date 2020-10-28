<?php

namespace StoryChief\StoryChief\FieldHandlers;

use Statamic\Entries\Entry;
use Statamic\Fields\Field;

class BaseFieldHandler implements FieldHandlerInterface{

  /** @var Entry */
  public $entry;

  /** @var Field */
  public $field;

  /** $var array */
  public $payload;

  /** @var mixed */
  public $payload_value;

  /**
   * BaseFieldHandler constructor.
   *
   * @param \Statamic\Entries\Entry $entry
   * @param \Statamic\Fields\Field $field
   * @param array $payload
   * @param $payload_value
   */
  public function __construct(Entry $entry, Field $field, array $payload, $payload_value)
  {
    $this->entry = $entry;
    $this->field = $field;
    $this->payload = $payload;
    $this->payload_value = $payload_value;
  }

  /**
   * @return mixed
   */
  public function getValue() {
    return $this->payload_value;
  }

  /**
   * @return void
   */
  public function setField(): void {
    $this->entry->set($this->field->handle(), $this->getValue());
  }
}
