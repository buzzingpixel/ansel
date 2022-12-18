<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\SourceHandling\Ee\Ee;

use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use BuzzingPixel\Ansel\Translations\TranslatorContract;
use ExpressionEngine\Addons\FilePicker\Service\FilePicker\Factory;
use ExpressionEngine\Model\File\UploadDestination;
use ExpressionEngine\Service\Model\Facade as RecordService;

use function assert;

class EeModalLink
{
    private Factory $filePickerFactory;

    private RecordService $recordService;

    private TranslatorContract $translator;

    public function __construct(
        Factory $filePickerFactory,
        RecordService $recordService,
        TranslatorContract $translator
    ) {
        $this->recordService     = $recordService;
        $this->filePickerFactory = $filePickerFactory;
        $this->translator        = $translator;
    }

    public function getLink(FieldSettingsCollection $fieldSettings): string
    {
        $directory = $this->recordService->get('UploadDestination')
            ->filter(
                'id',
                $fieldSettings->uploadLocation()->directoryId(),
            )
            ->first();

        assert($directory instanceof UploadDestination);

        /** @phpstan-ignore-next-line */
        $modalView = $directory->getProperty('default_modal_view');

        $picker = $this->filePickerFactory->make(
            $fieldSettings->uploadLocation()->directoryId(),
        );

        $link = $picker->getLink(
            $this->translator->getLine('choose_an_existing_image'),
        )
            ->enableFilters()
            ->enableUploads();

        if ($modalView === 'list') {
            $link->asList();
        } elseif ($modalView === 'thumb') {
            $link->asThumbs();
        }

        return $link->render();
    }
}
