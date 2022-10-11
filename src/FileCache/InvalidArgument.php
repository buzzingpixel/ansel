<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\FileCache;

use Exception;
use Psr\Cache\InvalidArgumentException;

class InvalidArgument extends Exception implements InvalidArgumentException
{
}
