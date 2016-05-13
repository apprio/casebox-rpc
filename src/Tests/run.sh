#!/usr/bin/env bash

set -e

function rmcache () {
    if [ -d $CACHE ]; then
        sudo rm -r $CACHE
    fi
}

DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
PHP=$( which php )

CACHE=$DIR/../../../../../var/cache/test

rmcache "$CACHE"

$PHP $DIR/../../../../bin/phpunit --colors --verbose --debug --configuration $DIR/phpunit-app.xml

rmcache "$CACHE"

$PHP $DIR/../../../../bin/phpunit --colors --verbose --debug --configuration $DIR/phpunit-api.xml

rmcache "$CACHE"
