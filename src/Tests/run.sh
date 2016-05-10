#!/usr/bin/env bash

set -e

DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
PHP=$( which php )

$PHP $DIR/../../../../bin/phpunit --colors --verbose --debug --configuration $DIR/phpunit.xml
