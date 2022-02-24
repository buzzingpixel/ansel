#!/usr/bin/env bash

set -e;

SUCCESS=false

while [ ${SUCCESS} == false ]; do
    { # try
        yarn js;

        SUCCESS=true;
    } || { # catch
        SUCCESS=false;
    }
done
