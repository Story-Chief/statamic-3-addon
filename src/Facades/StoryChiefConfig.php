<?php

namespace StoryChief\StoryChief\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class StoryChiefConfig
 *
 * @package StoryChief\StoryChief\Facades
 * @method static void set(array $payload)
 */
class StoryChiefConfig extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'storychief_config';
    }

}
