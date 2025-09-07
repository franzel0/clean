#!/bin/bash

echo "🚀 Preparing for deployment..."

# Clean up
php artisan optimize:clear

# Install production dependencies
composer install --no-dev --optimize-autoloader

# Cache everything for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Ready for deployment!"
echo "📁 Upload these folders/files to webspace:"
echo "   - All project files except node_modules, .git, tests"
echo "   - Include vendor/ and bootstrap/cache/"
echo "   - Don't forget composer.lock"
