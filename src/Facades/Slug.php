<?php

namespace StoryChief\StoryChief\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Slug
 *
 * @package StoryChief\StoryChief\Facades
 * @method static string unique(string $input) Generates a unique slug.
 */
class Slug extends Facade {

  /**
   * @return string
   */
  protected static function getFacadeAccessor() {
    return 'storychief_slug';
  }

}
