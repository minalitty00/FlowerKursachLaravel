# 📊 Руководство по резервному копированию базы данных

## 📁 Файлы для работы с БД

1. **database_backup.sql** - Структура базы данных (без данных)
2. **export_database.sh** - Скрипт экспорта для Linux/Mac
3. **export_database.ps1** - Скрипт экспорта для Windows

---

## 🔄 Экспорт базы данных (с данными)

### Windows

```powershell
.\export_database.ps1
```

Введите пароль MySQL когда попросит.

### Linux/Mac

```bash
chmod +x export_database.sh
./export_database.sh
```

Введите пароль MySQL когда попросит.

### Вручную

```bash
mysqldump -u root -p flowershopdb > backup.sql
```

---

## 📥 Импорт базы данных

### Вариант 1: Только структура (без данных)

```bash
# Создать БД
mysql -u root -p
CREATE DATABASE flowershopdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Импортировать структуру
mysql -u root -p flowershopdb < database_backup.sql
```

### Вариант 2: Полный бэкап (со всеми данными)

```bash
# Создать БД
mysql -u root -p
CREATE DATABASE flowershopdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Импортировать бэкап
mysql -u root -p flowershopdb < flowershopdb_backup_YYYYMMDD_HHMMSS.sql
```

---

## 🎯 Быстрые команды

### Экспорт только структуры (без данных)

```bash
mysqldump -u root -p --no-data flowershopdb > structure_only.sql
```

### Экспорт только данных (без структуры)

```bash
mysqldump -u root -p --no-create-info flowershopdb > data_only.sql
```

### Экспорт конкретной таблицы

```bash
mysqldump -u root -p flowershopdb users > users_backup.sql
```

### Экспорт с сжатием

```bash
mysqldump -u root -p flowershopdb | gzip > flowershopdb.sql.gz
```

### Импорт сжатого файла

```bash
gunzip < flowershopdb.sql.gz | mysql -u root -p flowershopdb
```

---

## 📋 Что включено в database_backup.sql

### Таблицы:

1. **users** - Пользователи (покупатели и администраторы)
2. **categories** - Категории товаров
3. **products** - Товары
4. **orders** - Заказы
5. **order_items** - Позиции заказов
6. **sessions** - Сессии пользователей
7. **cache** - Кэш приложения
8. **jobs** - Очередь задач
9. **personal_access_tokens** - API токены
10. **password_reset_tokens** - Токены сброса пароля
11. **migrations** - История миграций
12. **failed_jobs** - Неудачные задачи
13. **job_batches** - Пакеты задач
14. **cache_locks** - Блокировки кэша

### Начальные данные:

- ✅ Администратор (sonab2412@gmail.com / 13211321)
- ✅ 6 категорий товаров (Розы, Тюльпаны, Лилии, Орхидеи, Пионы, Хризантемы)

---

## 🔐 Безопасность

### Не забудьте:

1. **Не коммитить** файлы бэкапов в Git
2. **Хранить бэкапы** в безопасном месте
3. **Регулярно делать** резервные копии
4. **Тестировать** восстановление из бэкапа

### Добавьте в .gitignore:

```
*.sql
!database_backup.sql
flowershopdb_backup_*.sql
```

---

## 📅 Рекомендации по бэкапам

### Частота:

- **Разработка**: Перед крупными изменениями
- **Production**: Ежедневно (автоматически)
- **Перед обновлением**: Всегда!

### Хранение:

- Локально: последние 7 дней
- Облако: последние 30 дней
- Архив: ежемесячные бэкапы за год

---

## 🛠️ Автоматический бэкап (Linux)

### Создайте cron задачу:

```bash
# Редактировать crontab
crontab -e

# Добавить строку (бэкап каждый день в 2:00)
0 2 * * * /path/to/export_database.sh
```

### Создайте скрипт с ротацией:

```bash
#!/bin/bash
# auto_backup.sh

BACKUP_DIR="/path/to/backups"
DAYS_TO_KEEP=7

# Создать бэкап
mysqldump -u root -pPASSWORD flowershopdb > "$BACKUP_DIR/backup_$(date +%Y%m%d).sql"

# Удалить старые бэкапы
find $BACKUP_DIR -name "backup_*.sql" -mtime +$DAYS_TO_KEEP -delete
```

---

## 🔍 Проверка бэкапа

### Проверить размер файла:

```bash
ls -lh flowershopdb_backup_*.sql
```

### Проверить содержимое:

```bash
head -n 50 flowershopdb_backup_*.sql
```

### Проверить количество таблиц:

```bash
grep "CREATE TABLE" flowershopdb_backup_*.sql | wc -l
```

### Тестовое восстановление:

```bash
# Создать тестовую БД
mysql -u root -p
CREATE DATABASE flowershopdb_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Импортировать
mysql -u root -p flowershopdb_test < flowershopdb_backup_*.sql

# Проверить
mysql -u root -p flowershopdb_test
SHOW TABLES;
SELECT COUNT(*) FROM users;
EXIT;

# Удалить тестовую БД
mysql -u root -p
DROP DATABASE flowershopdb_test;
EXIT;
```

---

## 📊 Размеры таблиц

### Узнать размер каждой таблицы:

```sql
SELECT 
    table_name AS 'Таблица',
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Размер (MB)'
FROM information_schema.TABLES 
WHERE table_schema = 'flowershopdb'
ORDER BY (data_length + index_length) DESC;
```

---

## 🆘 Восстановление после сбоя

### Полное восстановление:

```bash
# 1. Удалить поврежденную БД
mysql -u root -p
DROP DATABASE flowershopdb;

# 2. Создать новую БД
CREATE DATABASE flowershopdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# 3. Импортировать бэкап
mysql -u root -p flowershopdb < flowershopdb_backup_LATEST.sql

# 4. Проверить
php artisan migrate:status
```

### Восстановление конкретной таблицы:

```bash
# Экспортировать таблицу из бэкапа
sed -n '/CREATE TABLE `users`/,/UNLOCK TABLES/p' backup.sql > users_only.sql

# Импортировать
mysql -u root -p flowershopdb < users_only.sql
```

---

## ✅ Чеклист перед переносом на другой компьютер

- [ ] Экспортировать БД с данными
- [ ] Скопировать файлы проекта
- [ ] Скопировать `.env` файл
- [ ] Скопировать `storage/app/public` (изображения)
- [ ] Проверить версии PHP, MySQL, Node.js
- [ ] Импортировать БД
- [ ] Запустить `composer install`
- [ ] Запустить `npm install`
- [ ] Запустить `php artisan storage:link`
- [ ] Проверить работу сайта

---

**Важно**: Всегда тестируйте восстановление из бэкапа!

**Последнее обновление**: 2026-04-28
