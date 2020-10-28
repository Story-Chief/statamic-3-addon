<?php

namespace StoryChief\StoryChief;

use Statamic\Providers\AddonServiceProvider;
use StoryChief\StoryChief\Helpers\Slug as StoryChiefSlug;

class StoryChiefServiceProvider extends AddonServiceProvider {

  protected $routes = [
    'web' => __DIR__ . '/../routes/web.php',
  ];

  public function register() {
    app()->bind('storychief_slug', function () {
      return new StoryChiefSlug();
    });
  }

}
