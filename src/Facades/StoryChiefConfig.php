<?php

namespace StoryChief\StoryChief\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class StorychiefConfig
 *
 * @package StoryChief\StoryChief\Facades
 * @method static string set(array $payload)
 */
class StorychiefConfig extends Facade {

  /**
   * @return string
   */
  protected static function getFacadeAccessor() {
    return 'storychief_config';
  }

}
