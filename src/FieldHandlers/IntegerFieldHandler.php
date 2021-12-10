<?php

namespace StoryChief\StoryChief\FieldHandlers;

class IntegerFieldHandler extends BaseFieldHandler
{

    /**
     * @inheritdoc
     *
     * @return int
     */
    public function getValue(): int
    {
        if (is_object($this->payload_value)) {
            return 0;
        }

        return intval($this->payload_value);
    }

}
