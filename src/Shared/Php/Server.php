<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared\Php;

use function is_string;

class Server
{
    public function isHttps(): bool
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
            (
                isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
                $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'
            );
    }

    public function serverName(): string
    {
        $serverName = $_SERVER['SERVER_NAME'] ?? '';

        if (! is_string($serverName)) {
            $serverName = '';
        }

        return $serverName;
    }

    public function serverSiteUrl(): string
    {
        $secure = $this->isHttps();

        $protocol = $secure ? 'https://' : 'http://';

        return $protocol . $this->serverName();
    }
}
