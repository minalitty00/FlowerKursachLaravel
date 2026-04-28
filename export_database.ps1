# Скрипт для экспорта базы данных flowershopdb (Windows)

Write-Host "🔄 Экспорт базы данных flowershopdb..." -ForegroundColor Cyan

# Параметры подключения (измените если нужно)
$DB_HOST = "localhost"
$DB_USER = "root"
$DB_NAME = "flowershopdb"
$TIMESTAMP = Get-Date -Format "yyyyMMdd_HHmmss"
$OUTPUT_FILE = "flowershopdb_backup_$TIMESTAMP.sql"

# Запрос пароля
$DB_PASSWORD = Read-Host "Введите пароль MySQL" -AsSecureString
$BSTR = [System.Runtime.InteropServices.Marshal]::SecureStringToBSTR($DB_PASSWORD)
$PlainPassword = [System.Runtime.InteropServices.Marshal]::PtrToStringAuto($BSTR)

# Экспорт базы данных
Write-Host "📦 Экспортирование..." -ForegroundColor Yellow

$mysqldumpPath = "mysqldump"  # Или полный путь: "C:\xampp\mysql\bin\mysqldump.exe"

& $mysqldumpPath -h $DB_HOST -u $DB_USER -p$PlainPassword $DB_NAME | Out-File -FilePath $OUTPUT_FILE -Encoding UTF8

if ($LASTEXITCODE -eq 0) {
    $fileSize = (Get-Item $OUTPUT_FILE).Length / 1KB
    Write-Host "✅ База данных успешно экспортирована в файл: $OUTPUT_FILE" -ForegroundColor Green
    Write-Host "📊 Размер файла: $([math]::Round($fileSize, 2)) KB" -ForegroundColor Green
    Write-Host ""
    Write-Host "Для импорта на другом компьютере используйте:" -ForegroundColor Cyan
    Write-Host "mysql -u root -p flowershopdb < $OUTPUT_FILE" -ForegroundColor White
} else {
    Write-Host "❌ Ошибка при экспорте базы данных" -ForegroundColor Red
    exit 1
}
