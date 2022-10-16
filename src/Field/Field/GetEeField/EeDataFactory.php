<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\GetEeField;

use BuzzingPixel\Ansel\Field\Field\FieldMetaEe;
use BuzzingPixel\Ansel\Field\Field\Persistence\AnselImageEeRecord;
use BuzzingPixel\Ansel\Field\Field\Persistence\Fetching\Direction;
use BuzzingPixel\Ansel\Field\Field\Persistence\Fetching\FetchParameters;
use BuzzingPixel\Ansel\Field\Field\Persistence\Fetching\Operator;
use BuzzingPixel\Ansel\Field\Field\Persistence\Fetching\OrderParameter;
use BuzzingPixel\Ansel\Field\Field\Persistence\Fetching\WhereParameter;
use BuzzingPixel\Ansel\Field\Field\Persistence\RecordService;
use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedData;
use BuzzingPixel\Ansel\Shared\EE\SiteMeta;

use function dd;
use function is_array;

class EeDataFactory
{
    private RecordService $recordService;

    private SiteMeta $siteMeta;

    public function __construct(
        RecordService $recordService,
        SiteMeta $siteMeta
    ) {
        $this->recordService = $recordService;
        $this->siteMeta      = $siteMeta;
    }

    /**
     * @param mixed $value
     */
    public function create(
        FieldMetaEe $fieldMeta,
        $value
    ): PostedData {
        if (is_array($value)) {
            return PostedData::fromArray($value);
        }

        $fetchParameters = new FetchParameters([
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
            $fetchParameters = $fetchParameters->withItem(
                new WhereParameter(
                    'row_id',
                    $fieldMeta->rowId(),
                ),
            );

            $fetchParameters = $fetchParameters->withItem(
                new WhereParameter(
                    'col_id',
                    $fieldMeta->colId(),
                ),
            );
        } else {
            $fetchParameters = $fetchParameters->withItem(
                new WhereParameter(
                    'row_id',
                    [
                        0,
                        '',
                    ],
                    Operator::IN(),
                ),
            );

            $fetchParameters = $fetchParameters->withItem(
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
            $fetchParameters,
            AnselImageEeRecord::class,
        );

        dd($imageRecords);
    }
}
