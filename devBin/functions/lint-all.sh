#!/usr/bin/env bash

function lint-all() {
    set -e;

    phpunit;
    phpstan;
    phpcs;
    eslint;
    typescript-check;
    stylelint;

    return 0;
}
