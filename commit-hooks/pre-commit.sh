#!/bin/sh

function jsonValue() {
    KEY=$1
    num=$2
    awk -F"[,:}]" '{for(i=1;i<=NF;i++){if($i~/'$KEY'\042/){print $(i+1)}}}' | tr -d '"' | sed -n ${num}p
}

MODE=$(cat ./assetsDist/js/manifest.json | jsonValue mode);
EXPECTED_MODE="production"

if [ ${MODE} != ${EXPECTED_MODE} ]; then
    cat <<\EOF
Error: Assets must be compiled in production mode. Run `yarn js`
EOF
    exit 1;
fi
