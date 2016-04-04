#!/bin/bash
echo "Fixing src/ folder"
php-cs-fixer fix src/ --level=symfony

echo "Fixing tests/ folder"
php-cs-fixer fix tests/ --level=symfony
