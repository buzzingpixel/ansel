#!/usr/bin/env bash

function phpcs() {
    php -d memory_limit=4G ./vendor/bin/phpcs

    return 0;
}

function phpcbf() {
    php -d memory_limit=4G ./vendor/bin/phpcbf;

    return 0;
}
