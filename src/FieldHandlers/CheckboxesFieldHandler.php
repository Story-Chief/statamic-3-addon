<?php

namespace StoryChief\StoryChief\FieldHandlers;

use Illuminate\Support\Arr;

class CheckboxesFieldHandler extends BaseFieldHandler
{

    /**
     * @inheritdoc
     *
     * @return array|null
     */
    public function getValue(): ?array
    {
        $value = is_array(
          $this->payload_value
        ) ? $this->payload_value[0] : $this->payload_value;
        $options = Arr::get($this->field->config(), 'options', []);

        return array_map(function ($value) use ($options) {
            foreach ($options as $option) {
                if (is_array($option)) {
                    if ($option['value'] == $value) {
                        return $option['key'];
                    }
                } else {
                    if ($option == $value) {
                        return $option;
                    }
                }
            }

            return null;
        }, explode(',', $value));
    }
}
