#!/bin/bash

# We don't install the packages at runtime. This is done when building the Docker image.
# The reason behind this is to be able to serve the container as fast as possible,
# without adding overhead to the scaling process.

# This file is only for small minor fixes on the project, like deploying the files to the CDN,
# caching the config, route, view, running migrations, etc.

# composer install --ignore-platform-reqs --optimize-autoloader --no-dev --prefer-dist

sed -i "s/'package_max_length' => 10 * 1024 * 1024,/'package_max_length' => 2000 * 1024 * 1024,/g" vendor/laravel/octane/src/Commands/StartSwooleCommand.php

#php artisan config:cache # will disable .env variables outside config
php artisan route:cache
php artisan view:cache
