<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared\Facades;

use ExpressionEngine\Service\Alert\Alert;
use ExpressionEngine\Service\Alert\AlertCollection;

class EeAlerts
{
    private AlertCollection $alertCollection;

    public function __construct(AlertCollection $alertCollection)
    {
        $this->alertCollection = $alertCollection;
    }

    /**
     * Using facade because EE's makeBanner method has an incorrect docblock
     * and makes code intelligence and linting suck and not work in other
     * parts of the code. We can tell PHPStan to ignore once here this way
     */
    public function makeBanner(): Alert
    {
        /** @phpstan-ignore-next-line */
        return $this->alertCollection->makeBanner();
    }
}
