#!/bin/bash
# Скрипт для исправления openapi.json после генерации

echo "🔧 Исправление openapi.json..."

# Генерация документации
echo "📝 Генерация документации..."
php artisan spectrum:generate --output=public/openapi.json

# Исправление server URL
echo "🔄 Исправление server URL..."
sed -i 's|"url": "http://localhost:8000/api"|"url": "http://localhost:8000"|g' public/openapi.json

echo "✅ Готово! Документация обновлена."
echo "📖 Откройте: http://localhost:8000/api-docs"
