<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\GetEeField;

use BuzzingPixel\Ansel\Field\Field\FieldMetaEe;
use BuzzingPixel\Ansel\Field\Field\Persistence\AnselFieldEeRecord;
use BuzzingPixel\Ansel\Field\Field\Persistence\AnselImageEeRecord;
use BuzzingPixel\Ansel\Field\Field\Persistence\Fetching\Direction;
use BuzzingPixel\Ansel\Field\Field\Persistence\Fetching\FetchParameters;
use BuzzingPixel\Ansel\Field\Field\Persistence\Fetching\Operator;
use BuzzingPixel\Ansel\Field\Field\Persistence\Fetching\OrderParameter;
use BuzzingPixel\Ansel\Field\Field\Persistence\Fetching\WhereParameter;
use BuzzingPixel\Ansel\Field\Field\Persistence\RecordService;
use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedData;
use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedDeletionsCollection;
use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedFieldData;
use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedFieldDataCollection;
use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedImage;
use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedImageCollection;
use BuzzingPixel\Ansel\Shared\SiteMeta;
use BuzzingPixel\Ansel\SourceHandling\Ee\EeSourceAdapterFactory;
use BuzzingPixel\Ansel\SourceHandling\File;
use BuzzingPixel\Ansel\SourceHandling\FileInstanceCollection;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

use function is_array;

// phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

class EeDataFactory
{
    private RecordService $recordService;

    private SiteMeta $siteMeta;

    private EeSourceAdapterFactory $sourceAdapterFactory;

    public function __construct(
        RecordService $recordService,
        SiteMeta $siteMeta,
        EeSourceAdapterFactory $sourceAdapterFactory
    ) {
        $this->recordService        = $recordService;
        $this->siteMeta             = $siteMeta;
        $this->sourceAdapterFactory = $sourceAdapterFactory;
    }

    /**
     * @param mixed $value
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function create(
        FieldMetaEe $fieldMeta,
        $value
    ): PostedData {
        if (is_array($value)) {
            return PostedData::fromArray($value);
        }

        $imageFetchParameters = new FetchParameters([
            new OrderParameter(
                'position',
                Direction::ASC(),
            ),
            new WhereParameter(
                'site_id',
                $this->siteMeta->siteId(),
            ),
            new WhereParameter(
                'content_id',
                $fieldMeta->contentId(),
            ),
            new WhereParameter(
                'field_id',
                $fieldMeta->fieldId(),
            ),
            new WhereParameter(
                'content_type',
                $fieldMeta->contentType()->getValue(),
            ),
        ]);

        if ($fieldMeta->rowId() !== null && $fieldMeta->colId() !== null) {
            $imageFetchParameters = $imageFetchParameters->withItem(
                new WhereParameter(
                    'row_id',
                    $fieldMeta->rowId(),
                ),
            );

            $imageFetchParameters = $imageFetchParameters->withItem(
                new WhereParameter(
                    'col_id',
                    $fieldMeta->colId(),
                ),
            );
        } else {
            $imageFetchParameters = $imageFetchParameters->withItem(
                new WhereParameter(
                    'row_id',
                    [
                        0,
                        '',
                    ],
                    Operator::IN(),
                ),
            );

            $imageFetchParameters = $imageFetchParameters->withItem(
                new WhereParameter(
                    'col_id',
                    [
                        0,
                        '',
                    ],
                    Operator::IN(),
                ),
            );
        }

        $imageRecords = $this->recordService->fetchRecords(
            $imageFetchParameters,
            AnselImageEeRecord::class,
        );

        $sourcesAndIds = [];

        $imageRecords->map(
            static function (
                AnselImageEeRecord $i
            ) use (&$sourcesAndIds): void {
                $type = $i->original_location_type;

                $sourcesAndIds[$type][] = $i->original_file_id;
            }
        );

        $sourceFiles = new FileInstanceCollection();

        foreach ($sourcesAndIds as $type => $ids) {
            $adapter = $this->sourceAdapterFactory->createInstanceByShortName(
                $type,
            );

            $sourceFiles = $sourceFiles->withAddedFiles(
                /** @phpstan-ignore-next-line */
                $adapter->getFilesByIdentifiers($ids)
            );
        }

        $fieldRecords = $this->recordService->fetchRecords(
            new FetchParameters([
                new WhereParameter(
                    'ansel_image_ansel_id',
                    $imageRecords->map(
                        static fn (
                            AnselImageEeRecord $r
                        ) => $r->ansel_id
                    ),
                    Operator::IN(),
                ),
            ]),
            AnselFieldEeRecord::class,
        );

        return new PostedData(
            new PostedImageCollection(
                $imageRecords->map(
                    static function (
                        AnselImageEeRecord $r
                    ) use (
                        $fieldRecords,
                        $sourceFiles
                    ): PostedImage {
                        $fieldRecords = $fieldRecords->filter(
                            static fn (
                                AnselFieldEeRecord $f
                            ) => $f->ansel_image_ansel_id === $r->ansel_id,
                        );

                        $sourceFile = $sourceFiles->filter(
                            static fn (
                                File $f
                            ) => $f->identifier() === (
                                (string) $r->original_file_id
                            ),
                        )->first();

                        return new PostedImage(
                            $r->ansel_id,
                            $sourceFile->url(),
                            $r->filename,
                            (string) $r->original_file_id,
                            (string) $r->x,
                            (string) $r->y,
                            (string) $r->width,
                            (string) $r->height,
                            (string) $r->focal_x,
                            (string) $r->focal_y,
                            new PostedFieldDataCollection(
                                $fieldRecords->map(
                                    static function (
                                        AnselFieldEeRecord $f
                                    ): PostedFieldData {
                                        return new PostedFieldData(
                                            $f->handle,
                                            $f->value,
                                        );
                                    }
                                )
                            ),
                        );
                    }
                )
            ),
            new PostedDeletionsCollection(),
        );
    }
}
