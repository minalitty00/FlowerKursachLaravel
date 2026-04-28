# Скрипт для исправления openapi.json после генерации
Write-Host "🔧 Исправление openapi.json..." -ForegroundColor Cyan

# Генерация документации
Write-Host "📝 Генерация документации..." -ForegroundColor Yellow
php artisan spectrum:generate --output=public/openapi.json

# Исправление server URL
Write-Host "🔄 Исправление server URL..." -ForegroundColor Yellow
$content = Get-Content public/openapi.json -Raw
$content = $content -replace '"url": "http://localhost:8000/api"', '"url": "http://localhost:8000"'
Set-Content public/openapi.json -Value $content

Write-Host "✅ Готово! Документация обновлена." -ForegroundColor Green
Write-Host "📖 Откройте: http://localhost:8000/api-docs" -ForegroundColor Cyan
