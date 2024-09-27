#!/bin/bash

curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.3/install.sh | bash
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"  # This loads nvm
[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"  # This loads nvm bash_completion
nvm install --lts --latest-npm

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"

php composer.phar install --ansi --no-interaction
"$HOME/.nvm/versions/node/v20.17.0/bin/npm" install --no-save
"$HOME/.nvm/versions/node/v20.17.0/bin/npm" run-script build

php bin/console dependencies install
php bin/console locales:compile

# Create database crpyting key from environment
echo "$SECRET_FILE" | base64 -d > ./config/glpicrypt.key

# Create database config file from environment
php bin/create_db_config.php
# Update the database
php bin/console db:update
