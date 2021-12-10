<?php

namespace StoryChief\StoryChief\FieldHandlers;

use Carbon\Carbon;

class DateFieldHandler extends BaseFieldHandler
{

    /**
     * @inheritdoc
     *
     * @return Carbon
     */
    public function getValue(): Carbon
    {
        return Carbon::parse($this->payload_value);
    }

}
