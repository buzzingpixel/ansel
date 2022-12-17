<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared\EE;

class EeGlobalFunctions
{
    public function showError(
        string $message,
        int $statusCode = 500,
        string $heading = 'Error'
    ): void {
        show_error(
            $message,
            $statusCode,
            $heading
        );
    }
}
