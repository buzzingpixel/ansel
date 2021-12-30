#!/usr/bin/env bash

function phpcs() {
    php74 -d memory_limit=4G ./vendor/bin/phpcs

    return 0;
}

function phpcbf() {
    php74 -d memory_limit=4G ./vendor/bin/phpcbf;

    return 0;
}
