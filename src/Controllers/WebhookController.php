<?php

namespace StoryChief\StoryChief\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Statamic\Entries\Entry;
use Statamic\Exceptions\NotFoundHttpException;
use Statamic\Facades\Entry as EntryService;
use StoryChief\StoryChief\Events\StoryChiefCreatedEvent;
use StoryChief\StoryChief\Events\StoryChiefCreatingEvent;
use StoryChief\StoryChief\Events\StoryChiefDeletedEvent;
use StoryChief\StoryChief\Events\StoryChiefDeletingEvent;
use StoryChief\StoryChief\Events\StoryChiefUpdatedEvent;
use StoryChief\StoryChief\Events\StoryChiefUpdatingEvent;
use StoryChief\StoryChief\Facades\Slug;
use StoryChief\StoryChief\Facades\StoryChiefConfig;
use StoryChief\StoryChief\StoryChiefMappingHandler;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class WebhookController
{

    protected $payload = [];

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(): JsonResponse
    {
        $this->payload = request()->all();
        $event = Arr::get($this->payload, 'meta.event');

        StoryChiefConfig::set($this->payload);

        try {
            switch ($event) {
                case 'publish':
                    return $this->handlePublishEvent();
                case 'update':
                    return $this->handleUpdateEvent();
                case 'delete':
                    return $this->handleDeleteEvent();
                case 'test':
                    return $this->handleTestEvent();
                default:
                    throw new BadRequestHttpException(
                      "Could not understand event '$event'"
                    );
            }
        } catch (Exception $e) {
            Log::error($e);

            return response()->json(
              [
                'errors'    => 'Sorry, something went wrong.',
                'exception' => get_class(
                  $e
                ),
                'message'   => $e->getMessage(),
                'trace'     => $e->getTrace(),
              ],
              $e->getCode() ?: 500
            );
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \ReflectionException
     * @throws \StoryChief\StoryChief\Exceptions\InvalidFieldHandleException
     * @throws \StoryChief\StoryChief\Exceptions\InvalidFieldTypeException
     */
    protected function handlePublishEvent(): JsonResponse
    {
        $slug = Arr::get($this->payload, 'data.seo_slug') ?: Arr::get(
          $this->payload,
          'data.title'
        );

        /** @var Entry $entry */
        $entry = EntryService::make();
        $entry->collection(config('storychief.collection'));
        $entry->blueprint(config('storychief.blueprint'));
        $entry->slug(Slug::unique($slug));
        $entry->locale(request()->get('data.language'));
        $entry->date(Carbon::now());
        $entry->published(true);

        $entry = (new StoryChiefMappingHandler($entry, $this->payload))->map();

        StoryChiefCreatingEvent::dispatch($entry, $this->payload);

        $entry->save();

        StoryChiefCreatedEvent::dispatch($entry, $this->payload);

        return response()->json(
          [
            'id'        => $entry->id(),
            'permalink' => $entry->absoluteUrl(),
          ]
        );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \ReflectionException
     * @throws \StoryChief\StoryChief\Exceptions\InvalidFieldHandleException
     * @throws \StoryChief\StoryChief\Exceptions\InvalidFieldTypeException
     */
    protected function handleUpdateEvent(): JsonResponse
    {
        $id = Arr::get($this->payload, 'data.external_id');

        /** @var Entry $entry */
        if (!$entry = EntryService::find($id)) {
            throw new NotFoundHttpException(
              "Could not find an entry with id $id", null, 404
            );
        }

        $entry = (new StoryChiefMappingHandler($entry, $this->payload))->map();

        StoryChiefUpdatingEvent::dispatch($entry, $this->payload);

        $entry->save();

        StoryChiefUpdatedEvent::dispatch($entry, $this->payload);

        return response()->json(
          [
            'id'        => $entry->id(),
            'permalink' => $entry->absoluteUrl(),
          ]
        );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    protected function handleDeleteEvent(): JsonResponse
    {
        $id = Arr::get($this->payload, 'data.external_id');

        /** @var Entry $entry */
        if ($entry = EntryService::find($id)) {
            StoryChiefDeletingEvent::dispatch($entry, $this->payload);

            $entry->delete();

            StoryChiefDeletedEvent::dispatch($entry, $this->payload);
        }

        return response()->json('Ok');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleTestEvent(): JsonResponse
    {
        return response()->json('Ok');
    }

}
