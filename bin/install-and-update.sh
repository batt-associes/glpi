#!/bin/bash

curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.3/install.sh | bash
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"  # This loads nvm
[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"  # This loads nvm bash_completion
nvm install --lts --latest-npm

php bin/console dependencies install
php bin/console locales:compile

# Create database crpyting key from environment
echo "$SECRET_FILE" | base64 -d > ./config/glpicrypt.key

# Create database config file from environment
php bin/create_db_config.php
# Update the database
php bin/console db:update
