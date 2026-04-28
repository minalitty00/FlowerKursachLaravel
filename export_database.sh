#!/bin/bash
# Скрипт для экспорта базы данных flowershopdb

echo "🔄 Экспорт базы данных flowershopdb..."

# Параметры подключения (измените если нужно)
DB_HOST="localhost"
DB_USER="root"
DB_NAME="flowershopdb"
OUTPUT_FILE="flowershopdb_backup_$(date +%Y%m%d_%H%M%S).sql"

# Экспорт базы данных
mysqldump -h $DB_HOST -u $DB_USER -p $DB_NAME > $OUTPUT_FILE

if [ $? -eq 0 ]; then
    echo "✅ База данных успешно экспортирована в файл: $OUTPUT_FILE"
    echo "📊 Размер файла: $(du -h $OUTPUT_FILE | cut -f1)"
else
    echo "❌ Ошибка при экспорте базы данных"
    exit 1
fi

echo ""
echo "Для импорта на другом компьютере используйте:"
echo "mysql -u root -p flowershopdb < $OUTPUT_FILE"
