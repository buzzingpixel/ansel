#!/usr/bin/env bash

function eslint() {
    yarn eslint --ext .js --ext .ts --ext .jsx --ext .tsx --ext .html --ext .vue --ext .mjs --no-error-on-unmatched-pattern assets cms src;

    return 0;
}

function eslint-fix() {
    yarn eslint --ext .js --ext .ts --ext .jsx --ext .tsx --ext .html --ext .vue --ext .mjs --no-error-on-unmatched-pattern assets cms src --fix;

    return 0;
}
