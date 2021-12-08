<?php

namespace StoryChief\StoryChief\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Statamic\Entries\Entry;

class StoryChiefUpdatingEvent
{
    use Dispatchable;

    public $entry;
    public $payload;

    public function __construct(Entry $entry, array $payload)
    {
        $this->entry = $entry;
        $this->payload = $payload;
    }
}
