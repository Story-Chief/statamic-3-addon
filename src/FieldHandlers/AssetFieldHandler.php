<?php

namespace StoryChief\StoryChief\FieldHandlers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Statamic\Entries\Entry;
use Statamic\Facades\Asset;
use Statamic\Facades\AssetContainer;
use Statamic\Fields\Field;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AssetFieldHandler extends BaseFieldHandler
{

    /** @var string */
    public $file_name;

    /**
     * @inheritdoc
     */
    public function __construct(
      Entry $entry,
      Field $field,
      array $payload,
      $payload_value
    ) {
        parent::__construct($entry, $field, $payload, $payload_value);

        $this->file_name = basename($payload_value);
    }

    /**
     * @inheritdoc
     *
     * @return string|null
     */
    public function getValue(): ?string
    {
        if (!filter_var($this->payload_value, FILTER_VALIDATE_URL)) {
            return null;
        }

        $assetContainer = AssetContainer::findByHandle(
          Arr::get($this->field->config(), 'container')
        );

        /** @var \Statamic\Assets\Asset $asset */
        $asset = Asset::make();
        $asset->container($assetContainer);
        $asset->path(ltrim($assetContainer->url(),"/")); // Set it to the disk folder of the container.

        $path = $this->createTempFile();
        $uploaded_file = new UploadedFile($path, $this->file_name);
        $asset->upload($uploaded_file)->save();

        $this->deleteTempFile();

        return $asset->path();
    }

    /**
     * @return string
     */
    protected function createTempFile(): string
    {
        Storage::disk('local')->put(
          $this->file_name,
          file_get_contents($this->payload_value)
        );

        return realpath(storage_path("/app/$this->file_name"));
    }

    /**
     * @return void
     */
    protected function deleteTempFile(): void
    {
        Storage::disk('local')->delete($this->file_name);
    }

}
