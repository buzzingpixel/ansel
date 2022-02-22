#!/usr/bin/env bash

function eslint() {
    yarn eslint --ext .js --ext .ts --ext .jsx --ext .tsx --ext .html --ext .vue --ext .mjs --no-error-on-unmatched-pattern assets/js assets/react/app --max-warnings=0;

    return 0;
}

function eslint-fix() {
    yarn eslint --ext .js --ext .ts --ext .jsx --ext .tsx --ext .html --ext .vue --ext .mjs --no-error-on-unmatched-pattern assets/js assets/react/app --fix --max-warnings=0;

    return 0;
}
