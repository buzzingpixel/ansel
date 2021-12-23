#!/usr/bin/env bash

function phpstan() {
    XDEBUG_MODE=off php -d memory_limit=4G ./vendor/bin/phpstan analyse cms src;

    return 0;
}
