#!/bin/bash

echo "$SECRET_FILE" | base64 -d > ./config/glpicrypt.key
# Start default script for PHP apps
"$HOME"/bin/run