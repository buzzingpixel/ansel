<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads;

use BuzzingPixel\Ansel\Field\Field\Uploads\UploadUrl\GetUploadUrlContract;
use BuzzingPixel\Ansel\Shared\EE\SiteMeta;
use Cp;

use function http_build_query;

class GetUploadUrlEe implements GetUploadUrlContract
{
    private Cp $cp;

    private SiteMeta $siteMeta;

    public function __construct(
        Cp $cp,
        SiteMeta $siteMeta
    ) {
        $this->cp       = $cp;
        $this->siteMeta = $siteMeta;
    }

    public function get(): string
    {
        /** @phpstan-ignore-next-line */
        $actionId = (string) $this->cp->fetch_action_id(
            'Ansel',
            'imageUploader'
        );

        return $this->siteMeta->frontEndUrl() .
            '?' .
            http_build_query(['ACT' => $actionId]);
    }
}
