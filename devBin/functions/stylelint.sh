#!/usr/bin/env bash

function stylelint() {
    yarn stylelint --allow-empty-input "assets/**/*.{css,pcss}" "src/**/*.{css,pcss}";

    return 0;
}

function stylelint-fix() {
    yarn stylelint --allow-empty-input "assets/**/*.{css,pcss}" "src/**/*.{css,pcss}" --fix;

    return 0;
}
