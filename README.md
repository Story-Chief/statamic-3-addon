# StoryChief addon for Statamic 3

## Description

Connect your Statamic website to [StoryChief](https://storychief.io) and publish straight to Statamic.

Looking for [Statamic 2](https://github.com/Story-Chief/statamic-addon)?

**This plugin:**

* Publishes articles straight from StoryChief
* Keeps your formatting like header tags, bold, links, lists etc
* Does not alter your website’s branding, by using your site’s CSS for styling

## Installation

1. Add the addon through the addon manager or `composer require storychief/statamic-storychief`
2. Publish the configuration `php artisan vendor:publish --tag=storychief-config`
3. Disable `TrimStrings` and `ConvertEmptyStringsToNull` middleware.

## HOW IT WORKS
#### 1. Create a Statamic channel
If you don't already have an account on StoryChief, [sign up](https://app.storychief.io/register)! here.
Once created, add a Statamic channel on your workspace and take note of the encryption key it gives you.

#### 2. Install addon
 - Install the addon through the Statamic addon manager of your website or by using composer:
 ```composer require storychief/statamic-storychief```

- Publish the configuration file: 
```php artisan vendor:publish --tag=storychief-config```

- Set your collection (and blueprint) handle in the foreseen config options, as wel as your field mapping.

#### 3. Disable TrimStrings and ConvertEmptyStringsToNull middleware.
Laravel (and thus Statamic) will register the Middleware `TrimStrings` and `ConvertEmptyStringsToNull` on a global level by default.
These can cause issues when validating the payload as they will manipulate them.
In order for the addon to work properly it is best to remove both of these middlewares from the global scope and add them back on the appropriate route groups.
The resulting `App\Http\Kernel` should look similar to: 
```
<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrustProxies::class,
        // **Removed TrimStrings and ConvertEmptyStringsToNull**
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\TrimStrings::class, // **ADDED**
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class, // **ADDED**
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
            \App\Http\Middleware\TrimStrings::class, // **ADDED**
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class, // **ADDED**
        ],
    ];

    ...
}
```

#### 4. Confirm
Set your website's url on your Statamic channel and press save. Your website should now be connected.


## REQUIREMENTS
This plugin requires a StoryChief account.
Not a StoryChief user yet? [Sign up for free](https://app.storychief.io/register)!

## Customize

This addon dispatched several [events](https://laravel.com/docs/8.x/events) so you can listen
to them when the addon saves or deletes an entry.

- creating: StoryChiefCreatingEvent
- created: StoryChiefCreatedEvent
- updating: StoryChiefUpdatingEvent
- updated: StoryChiefUpdatedEvent
- deleting: StoryChiefDeletingEvent
- deleted: StoryChiefDeletedEvent

You can start subscribing by adding a listener in your EventServiceProvider.

```
<?php 

namespace App\Providers;

...
use App\Listeners\HandleCollectionListener;
use StoryChief\StoryChief\Events\StoryChiefCreatingEvent;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ...
        StoryChiefCreatingEvent::class => [
            HandleCollectionListener::class,
        ]
    ];
}
```
Next is adding your listener in Laravel, you can for example alter the collection
based of a custom field value. That is part of the payload send from StoryChief.

1. Add a listener
2. Your Listener should not interact with the queue
   1. Remove the interface ShouldQueue and 
   1. remove the trait InteractsWithQueue after using `php artisan make:event xyz` 

```
<?php

namespace App\Listeners;

use StoryChief\StoryChief\Events\StoryChiefCreatingEvent;

class HandleCollectionListener
{
    public function handle(StoryChiefCreatingEvent $event)
    {
        $entry = $event->entry;
        $collectionValue = collect($event->payload['data']['custom_fields']['data'] ?? [])
            ->where('key', 'xyz_custom_field_name')
            ->first();

        if ($collectionValue && $collectionValue['value']) {
            // Alter the collection based on a custom field value
            $entry->collection($collectionValue['value']);
        }
    }
}

```


