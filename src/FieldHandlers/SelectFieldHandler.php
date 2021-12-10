<?php

namespace StoryChief\StoryChief\FieldHandlers;

use Illuminate\Support\Arr;

class SelectFieldHandler extends BaseFieldHandler
{

    /**
     * @inheritdoc
     *
     * @return array|null
     */
    public function getValue(): ?array
    {
        $values = !is_array($this->payload_value) ? explode(
          ',',
          $this->payload_value
        ) : $this->payload_value;

        $allows_multiple = Arr::get($this->field->config(), 'multiple', false);
        if (!$allows_multiple) {
            $values = array_slice($values, 0, 1);
        }

        $allows_additions = Arr::get($this->field->config(), 'taggable', false);
        if (!$allows_additions) {
            $values = $this->filterValues($values);
        }

        return $values;
    }

    /**
     * @param array $values
     *
     * @return array
     */
    protected function filterValues(array $values): array
    {
        $options = Arr::get($this->field->config(), 'options', []);

        return array_intersect(array_keys($options), $values);
    }

}
