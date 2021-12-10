<?php

namespace StoryChief\StoryChief;

use ReflectionClass;
use Statamic\Entries\Entry;
use Statamic\Facades\Collection;
use Statamic\Fields\Field;
use Statamic\Support\Arr;
use StoryChief\StoryChief\Exceptions\InvalidFieldHandleException;
use StoryChief\StoryChief\Exceptions\InvalidFieldTypeException;
use StoryChief\StoryChief\FieldHandlers\FieldHandlerInterface;

class StoryChiefMappingHandler
{

    /** @var array */
    protected $payload;

    /** @var Entry */
    protected $entry;

    public function __construct(Entry $entry, array $payload)
    {
        $this->entry = $entry;
        $this->payload = $payload;
    }

    /**
     * @return \Statamic\Entries\Entry
     * @throws \StoryChief\StoryChief\Exceptions\InvalidFieldTypeException
     * @throws \StoryChief\StoryChief\Exceptions\InvalidFieldHandleException
     * @throws \ReflectionException
     */
    public function map(): Entry
    {
        $field_mapping = array_filter(
          Arr::dot(config('storychief.mapping', []))
        );

        foreach ($field_mapping as $storychief_field => $statamic_field_handle) {
            $payload_value = $this->getPayloadFieldValueByKey(
              $storychief_field
            );
            $statamic_field = $this->getStatamicFieldByHandle(
              $statamic_field_handle
            );

            $this->setFieldValue($statamic_field, $payload_value);
        }

        return $this->entry;
    }

    /**
     * @param string $key
     *
     * @return string|string[]|null
     */
    protected function getPayloadFieldValueByKey(string $key)
    {
        if (explode('.', $key)[0] === 'custom_fields') {
            $key = explode('.', $key)[1];

            $custom_fields = Arr::get($this->payload, 'data.custom_fields', []);
            return Arr::get(
              Arr::first(
                $custom_fields,
                function ($custom_field) use ($key) {
                    return Arr::get($custom_field, 'key', null) === $key;
                }
              ),
              'value',
              null
            );
        }

        switch ($key) {
            case 'author_email':
                return Arr::get($this->payload, 'data.author.data.email', null);
            case 'author_name':
                $first = Arr::get(
                  $this->payload,
                  'data.author.data.first_name',
                  ''
                );
                $last = Arr::get(
                  $this->payload,
                  'data.author.data.last_name',
                  ''
                );
                return trim("$first $last");
            case 'featured_image':
                return Arr::get(
                  $this->payload,
                  'data.featured_image.data.sizes.full',
                  null
                );
            case 'tags':
            case 'categories':
                return array_column(
                  Arr::get($this->payload, "data.$key.data", []),
                  'name'
                );
            default:
                return Arr::get($this->payload, "data.$key");
        }
    }

    /**
     * @param string $handle
     *
     * @return \Statamic\Fields\Field
     * @throws \StoryChief\StoryChief\Exceptions\InvalidFieldHandleException
     */
    protected function getStatamicFieldByHandle(string $handle): Field
    {
        $field = Collection::findByHandle(config('storychief.collection'))
          ->entryBlueprint(config('storychief.blueprint'))
          ->fields()
          ->all()
          ->first(function (Field $value) use ($handle) {
              return $value->handle() === $handle;
          });

        if (!$field) {
            throw new InvalidFieldHandleException(
              "Could not find any field with handle '$handle'"
            );
        }

        return $field;
    }

    /**
     * @param \Statamic\Fields\Field $field
     * @param $value
     *
     * @throws \StoryChief\StoryChief\Exceptions\InvalidFieldTypeException
     * @throws \ReflectionException
     */
    protected function setFieldValue(Field $field, $value): void
    {
        $field_type = Arr::get($field->config(), 'type');
        $field_handler_class = config(
          "storychief.fieldtypes.$field_type",
          null
        );

        if (is_null($field_handler_class)) {
            throw new InvalidFieldTypeException(
              "No field handler for type '$field_type'"
            );
        }

        $class = new ReflectionClass($field_handler_class);
        if (!$class->implementsInterface(FieldHandlerInterface::class)) {
            throw new InvalidFieldTypeException(
              "Field handler '$field_handler_class' should implement interface " . FieldHandlerInterface::class
            );
        }

        $field_handler = new $field_handler_class(
          $this->entry,
          $field,
          $this->payload,
          $value
        );
        $field_handler->setField();
    }

}
