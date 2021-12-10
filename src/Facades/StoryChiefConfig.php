<?php

namespace StoryChief\StoryChief\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class StorychiefConfig
 *
 * @package StoryChief\StoryChief\Facades
 * @method static void set(array $payload)
 */
class StorychiefConfig extends Facade {

  /**
   * @return string
   */
  protected static function getFacadeAccessor(): string  {
    return 'storychief_config';
  }

}
