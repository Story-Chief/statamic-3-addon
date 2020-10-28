<?php

return [

  /**
   * Your StoryChief encryption key.
   * Create a Statamic channel on https://app.storychief.io to get it.
   */
  'encryption_key' => env('STORYCHIEF_ENCRYPTION_KEY', ''),

  /**
   * The collection handle StoryChief should publish to.
   */
  'collection'     => NULL,

  /**
   * The blueprint handle StoryChief should use on your collection.
   */
  'blueprint'      => NULL,

  /**
   * Your field mapping.
   */
  'mapping'        => [
    'title'           => 'title',
    'content'         => 'content',
    'excerpt'         => NULL,
    'featured_image'  => NULL,
    'categories'      => NULL,
    'tags'            => NULL,
    'author_email'    => NULL,
    'author_name'     => NULL,
    'seo_title'       => NULL,
    'seo_description' => NULL,
    'custom_fields'   => [
      // 'your-custom-field-code' => 'field_handler'
    ],
  ],

  /**
   * Your configured field handlers.
   * Add addition field handlers by creating a class
   * implementing StoryChief\StoryChief\FieldHandlers\FieldHandlerInterface.
   */
  'fieldtypes' => [
    'text'     => \StoryChief\StoryChief\FieldHandlers\BaseFieldHandler::class,
    'textarea' => \StoryChief\StoryChief\FieldHandlers\BaseFieldHandler::class,
    'html'     => \StoryChief\StoryChief\FieldHandlers\BaseFieldHandler::class,
    'markdown' => \StoryChief\StoryChief\FieldHandlers\BaseFieldHandler::class,
    'bard'     => \StoryChief\StoryChief\FieldHandlers\BardFieldHandler::class,
    'integer'  => \StoryChief\StoryChief\FieldHandlers\IntegerFieldHandler::class,
    'date'     => \StoryChief\StoryChief\FieldHandlers\DateFieldHandler::class,
    'link'     => \StoryChief\StoryChief\FieldHandlers\LinkFieldHandler::class,
    'radio'    => \StoryChief\StoryChief\FieldHandlers\RadioFieldHandler::class,
    'select'   => \StoryChief\StoryChief\FieldHandlers\SelectFieldHandler::class,
    'terms'    => \StoryChief\StoryChief\FieldHandlers\TermsFieldHandler::class,
    'users'    => \StoryChief\StoryChief\FieldHandlers\UserFieldHandler::class,
    'assets'   => \StoryChief\StoryChief\FieldHandlers\AssetFieldHandler::class,
  ],
];

