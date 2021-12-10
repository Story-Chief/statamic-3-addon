<?php

namespace StoryChief\StoryChief\FieldHandlers;

use Illuminate\Support\Arr;
use StoryChief\StoryChief\Exceptions\InvalidFieldTypeException;

class BardFieldHandler extends BaseFieldHandler
{

    /**
     * @inheritdoc
     *
     * @return string
     *
     * @throws \StoryChief\StoryChief\Exceptions\InvalidFieldTypeException
     */
    public function getValue()
    {
        if (!Arr::get($this->field->config(), 'save_html', false)) {
            throw new InvalidFieldTypeException(
              'The bard field type is only supported when save_html is on.'
            );
        }

        return $this->payload_value;
    }

}
