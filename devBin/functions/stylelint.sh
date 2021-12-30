#!/usr/bin/env bash

function stylelint() {
    yarn stylelint --allow-empty-input "assets/**/*.{css,pcss,html,twig}" "src/**/*.{css,pcss,html,twig}";

    return 0;
}

function stylelint-fix() {
    yarn stylelint --allow-empty-input "assets/**/*.{css,pcss,html,twig}" "src/**/*.{css,pcss,html,twig}" --fix;

    return 0;
}
