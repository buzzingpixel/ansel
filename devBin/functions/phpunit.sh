#!/usr/bin/env bash

function phpunit() {
    XDEBUG_MODE=coverage php ./vendor/bin/phpunit;

    return 0;
}
