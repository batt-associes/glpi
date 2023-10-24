#!/bin/bash

# Create database crpyting key from environment
echo "$SECRET_FILE" | base64 -d > ./config/glpicrypt.key

# Create database config file from environment
php create_db_config.php

# Start default script for PHP apps
"$HOME"/bin/run
