<?php

namespace StoryChief\StoryChief;

use Statamic\Providers\AddonServiceProvider;
use StoryChief\StoryChief\Helpers\Slug as StoryChiefSlug;

class StoryChiefServiceProvider extends AddonServiceProvider {

  public function boot() {
    parent::boot();
    $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
  }

  public function register() {
    app()->bind('storychief_slug', function () {
      return new StoryChiefSlug();
    });
  }

}
