<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\FileChooserModalLink;

use BuzzingPixel\Ansel\EeSourceHandling\Ee\EeModalLink;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;

class FileChooserModalLinkFactory
{
    private EeModalLink $eeModalLink;

    private AssetsModalLink $assetsModalLink;

    private TreasuryModalLink $treasuryModalLink;

    public function __construct(
        EeModalLink $eeModalLink,
        AssetsModalLink $assetsModalLink,
        TreasuryModalLink $treasuryModalLink
    ) {
        $this->eeModalLink       = $eeModalLink;
        $this->assetsModalLink   = $assetsModalLink;
        $this->treasuryModalLink = $treasuryModalLink;
    }

    public function getLink(FieldSettingsCollection $fieldSettings): string
    {
        $uploadLocation = $fieldSettings->uploadLocation();

        switch ($uploadLocation->directoryType()) {
            case 'ee':
                return $this->eeModalLink->getLink(
                    $fieldSettings,
                );

            case 'treasury':
                return $this->treasuryModalLink->getLink(
                    $fieldSettings,
                );

            case 'assets':
                return $this->assetsModalLink->getLink(
                    $fieldSettings,
                );
        }

        return '';
    }
}
