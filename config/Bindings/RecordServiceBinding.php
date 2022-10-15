<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use BuzzingPixel\Ansel\Field\Field\Persistence\Craft\RecordServiceCraft;
use BuzzingPixel\Ansel\Field\Field\Persistence\Ee\RecordServiceEe;
use BuzzingPixel\Ansel\Field\Field\Persistence\RecordService;
use Psr\Container\ContainerInterface;
use RuntimeException;

class RecordServiceBinding
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            RecordService::class => static function (
                ContainerInterface $container
            ): RecordService {
                switch (ANSEL_ENV) {
                    /** @phpstan-ignore-next-line */
                    case 'ee':
                        /** @phpstan-ignore-next-line */
                        return $container->get(RecordServiceEe::class);

                    /** @phpstan-ignore-next-line */
                    case 'craft':
                        /** @phpstan-ignore-next-line */
                        return $container->get(RecordServiceCraft::class);

                    default:
                        $msg = 'Class is not implemented for platform ';

                        throw new RuntimeException($msg . ANSEL_ENV);
                }
            },
        ];
    }
}
