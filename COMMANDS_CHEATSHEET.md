# 🎯 Шпаргалка по командам проекта "Флора"

Быстрый справочник всех полезных команд для работы с проектом.

---

## 🚀 Первый запуск

```bash
# Установка зависимостей
composer install
npm install

# Настройка окружения
cp .env.example .env
php artisan key:generate

# База данных
php artisan migrate
php artisan db:seed
php artisan storage:link

# Запуск
php artisan serve          # Терминал 1
npm run dev                # Терминал 2
```

---

## 🔄 Ежедневная разработка

### Запуск проекта
```bash
php artisan serve          # Laravel сервер (http://localhost:8000)
npm run dev                # Vite dev server (hot reload)
```

### Остановка
```
Ctrl + C                   # В каждом терминале
```

---

## 🗄️ База данных

### Миграции
```bash
php artisan migrate                    # Запуск миграций
php artisan migrate:rollback           # Откат последней миграции
php artisan migrate:fresh              # Сброс и повторный запуск
php artisan migrate:fresh --seed       # Сброс + начальные данные
php artisan migrate:status             # Статус миграций
```

### Seeders
```bash
php artisan db:seed                    # Все seeders
php artisan db:seed --class=AdminUserSeeder  # Конкретный seeder
```

### Tinker (консоль)
```bash
php artisan tinker                     # Запуск консоли

# Примеры в tinker:
User::all()                            # Все пользователи
User::find(1)                          # Пользователь по ID
Product::count()                       # Количество товаров
```

---

## 🧹 Очистка кэша

### Все кэши
```bash
php artisan optimize:clear             # Очистить все кэши
```

### По отдельности
```bash
php artisan config:clear               # Кэш конфигурации
php artisan cache:clear                # Кэш приложения
php artisan route:clear                # Кэш маршрутов
php artisan view:clear                 # Кэш views
php artisan event:clear                # Кэш событий
```

---

## 📖 API Документация

### Генерация
```bash
# Windows
.\fix-openapi.ps1

# Linux/Mac
bash fix-openapi.sh

# Вручную
php artisan spectrum:generate --output=public/openapi.json
```

### Режим разработки
```bash
php artisan spectrum:watch             # Автообновление документации
# Откроется на http://localhost:8080
```

### Очистка кэша Spectrum
```bash
# Windows
Remove-Item -Recurse -Force storage/app/spectrum/cache

# Linux/Mac
rm -rf storage/app/spectrum/cache
```

---

## 🧪 Тестирование

### Запуск тестов
```bash
php artisan test                       # Все тесты
php artisan test --testsuite=Unit      # Только Unit тесты
php artisan test --testsuite=Feature   # Только Feature тесты
php artisan test --filter=ProductTest  # Конкретный тест
php artisan test --coverage            # С покрытием кода
```

---

## 📦 Composer

### Установка и обновление
```bash
composer install                       # Установка зависимостей
composer update                        # Обновление зависимостей
composer dump-autoload                 # Обновление autoload
composer require package/name          # Добавить пакет
composer remove package/name           # Удалить пакет
```

### Оптимизация
```bash
composer install --optimize-autoloader --no-dev  # Production
```

---

## 📦 NPM

### Установка и обновление
```bash
npm install                            # Установка зависимостей
npm update                             # Обновление зависимостей
npm install package-name               # Добавить пакет
npm uninstall package-name             # Удалить пакет
```

### Сборка
```bash
npm run dev                            # Development с hot reload
npm run build                          # Production build
```

### Очистка
```bash
npm cache clean --force                # Очистка кэша
rm -rf node_modules package-lock.json  # Полная очистка
npm install                            # Переустановка
```

---

## 🔐 Пользователи

### Создание администратора
```bash
php artisan tinker

User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'role' => 'admin'
]);
exit
```

### Сброс пароля
```bash
php artisan tinker

$user = User::where('email', 'email@example.com')->first();
$user->password = bcrypt('новый_пароль');
$user->save();
exit
```

---

## 🛠️ Artisan команды

### Информация
```bash
php artisan list                       # Все команды
php artisan route:list                 # Все маршруты
php artisan route:list --name=api      # Маршруты с именем api
php artisan about                      # Информация о приложении
```

### Генерация кода
```bash
php artisan make:controller NameController  # Контроллер
php artisan make:model Name                 # Модель
php artisan make:migration create_table     # Миграция
php artisan make:seeder NameSeeder          # Seeder
php artisan make:request NameRequest        # Form Request
php artisan make:middleware NameMiddleware  # Middleware
```

---

## 🔍 Отладка

### Логи
```bash
# Linux/Mac
tail -f storage/logs/laravel.log       # Просмотр в реальном времени

# Windows PowerShell
Get-Content storage/logs/laravel.log -Wait -Tail 50
```

### Включение отладки
```bash
# В .env
APP_DEBUG=true
```

---

## 🚢 Production

### Подготовка
```bash
# Компиляция ассетов
npm run build

# Кэширование
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Оптимизация
composer install --optimize-autoloader --no-dev
```

### Настройка .env
```env
APP_ENV=production
APP_DEBUG=false
```

---

## 🗄️ MariaDB/MySQL

### Подключение
```bash
mysql -u root -p                       # Вход в MySQL
```

### Команды в MySQL
```sql
SHOW DATABASES;                        -- Список БД
USE flowershopdb;                      -- Выбрать БД
SHOW TABLES;                           -- Список таблиц
DESCRIBE users;                        -- Структура таблицы
SELECT * FROM users;                   -- Все пользователи
EXIT;                                  -- Выход
```

### Создание БД
```sql
CREATE DATABASE flowershopdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Удаление БД
```sql
DROP DATABASE flowershopdb;
```

---

## 🔄 Git

### Базовые команды
```bash
git status                             # Статус
git add .                              # Добавить все файлы
git commit -m "Сообщение"              # Коммит
git push                               # Отправить на сервер
git pull                               # Получить с сервера
```

### Ветки
```bash
git branch                             # Список веток
git checkout -b feature-name           # Создать и переключиться
git checkout main                      # Переключиться на main
git merge feature-name                 # Слить ветку
```

---

## 📊 Полезные команды

### Проверка версий
```bash
php -v                                 # Версия PHP
composer -V                            # Версия Composer
node -v                                # Версия Node.js
npm -v                                 # Версия npm
mysql -V                               # Версия MySQL/MariaDB
git --version                          # Версия Git
```

### Права доступа (Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Размер проекта
```bash
# Linux/Mac
du -sh .                               # Размер папки

# Windows PowerShell
(Get-ChildItem -Recurse | Measure-Object -Property Length -Sum).Sum / 1MB
```

---

## 🎯 Быстрые сценарии

### Полный сброс проекта
```bash
# Очистка
php artisan optimize:clear
rm -rf node_modules package-lock.json
composer clear-cache

# Переустановка
composer install
npm install

# База данных
php artisan migrate:fresh --seed

# Запуск
php artisan serve
npm run dev
```

### Обновление после git pull
```bash
composer install
npm install
php artisan migrate
php artisan optimize:clear
```

### Быстрая проверка работоспособности
```bash
php artisan about                      # Информация о приложении
php artisan migrate:status             # Статус миграций
php artisan route:list | grep api      # API маршруты
php artisan test                       # Запуск тестов
```

---

## 🆘 Экстренная помощь

### Проект не запускается
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan optimize:clear
```

### База данных не работает
```bash
# Проверка подключения
php artisan migrate:status

# Пересоздание
php artisan migrate:fresh --seed
```

### Ошибки после обновления
```bash
composer dump-autoload
php artisan optimize:clear
npm run build
```

---

## 📝 Примечания

- Все команды выполняются из корневой папки проекта
- Для Windows используйте PowerShell или Git Bash
- Для Linux/Mac используйте Terminal
- Замените `php` на `php8.2` если нужна конкретная версия

---

**Сохраните эту шпаргалку** для быстрого доступа к командам! 📌

**Последнее обновление**: 2026-04-28
