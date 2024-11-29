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


## HOW IT WORKS

#### 1. Create a Statamic channel

If you don't already have an account on StoryChief, [sign up](https://app.storychief.io/register)! here. Once created,
add a Statamic channel on your workspace and take note of the encryption key it gives you.

#### 2. Install addon

- Install the addon through the Statamic addon manager of your website or by using composer:
  ```composer require storychief/statamic-storychief```

- Publish the configuration file:
  ```php artisan vendor:publish --tag=storychief-config```

- Set you collection (and blueprint) handle in the foreseen config options, as wel as your field mapping.

#### 3. Confirm

Set your website's url on your Statamic channel and press save. Your website should now be connected.

## REQUIREMENTS

This plugin requires a StoryChief account. Not a StoryChief user
yet? [Sign up for free](https://app.storychief.io/register)!
