<?php

namespace StoryChief\StoryChief\Helpers;

use Illuminate\Support\Str;
use Statamic\Facades\Entry;

class Slug {

  public function unique(string $input): string {
    $unique_slug = $slug = $this->sanitize($input);

    while (Entry::findBySlug($unique_slug, config('storychief.collection')) !== null) {
      $unique_slug = $slug . '-' . Str::random(6);
    }

    return $unique_slug;
  }

  /**
   * @param string $slug
   *
   * @return string
   */
  protected function sanitize(string $slug): string {
    return Str::slug($slug, '-');
  }

}
