#!/usr/bin/env bash

function lint-all() {
    set -e;

    phpunit;
    phpstan;
    phpcs;
    eslint;
    stylelint;

    return 0;
}
