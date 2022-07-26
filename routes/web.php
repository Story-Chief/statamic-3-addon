<?php

use App\Http\Middleware\TrimStrings;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Support\Facades\Route;
use StoryChief\StoryChief\Controllers\WebhookController as StoryChiefWebhookController;
use StoryChief\StoryChief\Middleware\HmacCheckMiddleware as StoryChiefHmacCheckMiddleware;

Route::post(
  config('statamic.routes.action') . '/StoryChief/webhook',
  [StoryChiefWebhookController::class, 'handle']
)
  ->middleware([
      StoryChiefHmacCheckMiddleware::class,
  ])
  ->withoutMiddleware([
      TrimStrings::class,
      ConvertEmptyStringsToNull::class  
  ]);
