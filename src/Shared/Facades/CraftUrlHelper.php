<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared\Facades;

use craft\helpers\UrlHelper;

// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification

/**
 * @codeCoverageIgnore
 */
class CraftUrlHelper
{
    /**
     * @param array|string|null $params
     *
     * @phpstan-ignore-next-line
     */
    public function cpUrl(
        string $path = '',
        $params = null,
        ?string $scheme = null
    ): string {
        return UrlHelper::cpUrl(
            $path,
            $params,
            $scheme,
        );
    }
}
