DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

source $DIR/config/environment.sh

cp -r app assets config lib sql vendor index.php composer.json ~/public_html/

cd ~/public_html
php composer.phar dump-autoload
