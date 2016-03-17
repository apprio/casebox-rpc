
DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
$DIR/../../../../../vendor/bin/phpunit --colors --verbose --debug --bootstrap $DIR/bootstrap.php $DIR/rpc