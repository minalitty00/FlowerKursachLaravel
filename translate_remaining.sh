#!/bin/bash

# Script to complete Russian localization
# This script will be used as a reference for manual translation

echo "=== Localization Script ==="
echo "This script provides commands to complete the localization"
echo ""

echo "Step 1: Update composer autoload"
composer dump-autoload

echo ""
echo "Step 2: Clear Laravel caches"
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo ""
echo "=== Localization Complete ==="
echo "Please restart your Laravel server:"
echo "  php artisan serve"
echo ""
echo "And restart Vite:"
echo "  npm run dev"
