# Flower Shop System

Полнофункциональная система цветочного магазина на Laravel с MariaDB. Включает RESTful API, клиентское веб-приложение на Blade и административную панель.

## 📖 Документация

**[📚 Полный индекс документации](DOCUMENTATION_INDEX.md)** - Навигация по всей документации проекта  
**[🎯 Шпаргалка по командам](COMMANDS_CHEATSHEET.md)** - Быстрый справочник всех команд

### Быстрые ссылки:
- **[📊 Краткое резюме проекта](PROJECT_SUMMARY.md)** - Обзор всего проекта
- **[⚡ Быстрое развертывание](QUICK_DEPLOY.md)** - 5 шагов до запуска проекта
- **[📦 Полная инструкция по развертыванию](DEPLOYMENT_GUIDE.md)** - Подробное руководство для нового компьютера
- **[❓ FAQ - Часто задаваемые вопросы](FAQ.md)** - Ответы на популярные вопросы
- **[🔧 Исправление ошибки 419 в API](API_DOCS_FIX.md)** - Решение проблемы CSRF в Swagger UI
- **[📚 Руководство по Laravel Spectrum](LARAVEL_SPECTRUM_GUIDE.md)** - Работа с API документацией
- **[📋 Сводка установки Spectrum](SPECTRUM_INSTALLATION_SUMMARY.md)** - Краткая информация о Spectrum
- **[📝 История изменений](CHANGELOG.md)** - Версии и изменения проекта

## Возможности

- 🌸 **Каталог товаров** - управление товарами с изображениями, категориями и остатками
- 🛒 **Корзина покупок** - добавление товаров, управление количеством
- 📦 **Обработка заказов** - создание заказов, отслеживание статусов
- 👥 **Управление пользователями** - регистрация, аутентификация, роли (User/Admin)
- 📊 **Отчеты о выручке** - ежемесячная статистика продаж
- 🔐 **Безопасность** - CSRF защита, rate limiting, хэширование паролей
- 📱 **Responsive дизайн** - адаптивный интерфейс для всех устройств
- 📖 **API Документация** - автоматически генерируемая OpenAPI 3.0 документация

## Технологический стек

- **Backend**: Laravel 12.x, PHP 8.2+
- **Database**: MariaDB (MySQL-compatible)
- **Frontend**: Blade Templates, Tailwind CSS
- **Asset Bundler**: Vite
- **Authentication**: Laravel Session-based auth
- **Testing**: PHPUnit

## Требования к системе

- PHP >= 8.2
- Composer
- Node.js >= 18.x и npm
- MariaDB >= 10.6 или MySQL >= 8.0
- Расширения PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

## Установка

### 1. Клонирование репозитория

```bash
git clone <repository-url>
cd flower-shop-system
```

### 2. Установка зависимостей

```bash
# Установка PHP зависимостей
composer install

# Установка Node.js зависимостей
npm install
```

### 3. Настройка окружения

```bash
# Копирование файла конфигурации
cp .env.example .env

# Генерация ключа приложения
php artisan key:generate
```

### 4. Настройка базы данных

Отредактируйте файл `.env` и укажите параметры подключения к MariaDB:

```env
DB_CONNECTION=mariadb
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=flowershopdb
DB_USERNAME=root
DB_PASSWORD=your_password
```

Создайте базу данных:

```bash
# Войдите в MariaDB
mysql -u root -p

# Создайте базу данных
CREATE DATABASE flowershopdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 5. Запуск миграций и сидеров

```bash
# Запуск миграций
php artisan migrate

# Заполнение базы данных начальными данными
php artisan db:seed
```



Это создаст:
- Тестового администратора (email: admin@example.com, password: password)
- Базовые категории товаров (Розы, Тюльпаны, Лилии и т.д.)

### 6. Настройка хранилища файлов

```bash
# Создание символической ссылки для публичного хранилища
php artisan storage:link
```

Это создаст ссылку `public/storage` -> `storage/app/public` для доступа к загруженным изображениям.

### 7. Запуск приложения

Откройте два терминала:

**Терминал 1 - Laravel сервер:**
```bash
php artisan serve
```

**Терминал 2 - Vite dev server:**
```bash
npm run dev
```

Приложение будет доступно по адресу: http://localhost:8000

## API Документация

Проект использует **Laravel Spectrum** для автоматической генерации OpenAPI 3.0 документации без необходимости писать аннотации.

### Просмотр документации

После запуска приложения, документация доступна по адресу:
- **Swagger UI**: http://localhost:8000/api-docs

### Генерация документации

```bash
# Windows PowerShell
.\fix-openapi.ps1

# Linux/Mac
bash fix-openapi.sh

# Или вручную:
php artisan spectrum:generate --output=public/openapi.json
```

**Важно:** После генерации документации файл автоматически сохраняется в `public/openapi.json` и сразу доступен через браузер.

### Режим разработки с автообновлением

```bash
# Запуск watcher с hot reload
php artisan spectrum:watch

# Документация будет автоматически обновляться при изменении кода
# Посетите http://localhost:8080 для просмотра
```

### Возможности Laravel Spectrum

- ✅ **Нулевая конфигурация** - работает из коробки без аннотаций
- ✅ **Умное определение** - автоматически распознает FormRequest валидацию
- ✅ **API Resources** - поддержка Laravel API Resources
- ✅ **Обновление в реальном времени** - hot reload при изменениях
- ✅ **Кэширование** - умное кэширование для быстрой генерации

Документация автоматически включает:
- Все API endpoints с параметрами запросов
- Правила валидации из FormRequest классов
- Структуры ответов
- Коды ошибок и их описания
- Требования аутентификации

## Тестовые учетные данные

### Администратор
- Email: `admin@example.com`
- Password: `password`

### Обычный пользователь
Зарегистрируйтесь через форму регистрации на сайте.

## API Endpoints

### Аутентификация
- `POST /api/register` - Регистрация пользователя
- `POST /api/login` - Вход в систему
- `POST /api/logout` - Выход из системы

### Товары
- `GET /api/products` - Список товаров (с фильтрацией и поиском)
- `GET /api/products/{id}` - Детали товара
- `POST /api/products` - Создание товара (admin)
- `PUT /api/products/{id}` - Обновление товара (admin)
- `DELETE /api/products/{id}` - Удаление товара (admin)

### Категории
- `GET /api/categories` - Список категорий
- `POST /api/categories` - Создание категории (admin)
- `PUT /api/categories/{id}` - Обновление категории (admin)
- `DELETE /api/categories/{id}` - Удаление категории (admin)

### Корзина
- `GET /api/cart` - Просмотр корзины
- `POST /api/cart/add` - Добавление в корзину
- `PUT /api/cart/update` - Обновление количества
- `DELETE /api/cart/remove` - Удаление из корзины

### Заказы
- `GET /api/orders` - Список заказов (пользователя или всех для admin)
- `GET /api/orders/{id}` - Детали заказа
- `POST /api/orders` - Создание заказа
- `PUT /api/orders/{id}/status` - Обновление статуса (admin)

### Отчеты
- `GET /api/admin/revenue` - Отчет о выручке (admin)

## Веб-маршруты

### Публичные страницы
- `GET /` - Главная страница
- `GET /products` - Каталог товаров
- `GET /products/{id}` - Страница товара
- `GET /login` - Страница входа
- `GET /register` - Страница регистрации

### Защищенные страницы (требуется аутентификация)
- `GET /cart` - Корзина
- `GET /orders` - История заказов
- `GET /orders/{id}` - Детали заказа
- `GET /checkout` - Оформление заказа

### Административная панель (требуется роль admin)
- `GET /admin/dashboard` - Панель управления
- `GET /admin/products` - Управление товарами
- `GET /admin/categories` - Управление категориями
- `GET /admin/orders` - Управление заказами
- `GET /admin/revenue` - Отчеты о выручке

## Тестирование

```bash
# Запуск всех тестов
php artisan test

# Запуск только unit-тестов
php artisan test --testsuite=Unit

# Запуск только feature-тестов
php artisan test --testsuite=Feature

# Запуск с покрытием кода
php artisan test --coverage
```

## Конфигурация

### Сессии
- Время жизни сессии: 120 минут
- Драйвер: database
- Корзина хранится в сессии

### Хранилище файлов
- Диск по умолчанию: public
- Изображения товаров: `storage/app/public/products`
- Максимальный размер изображения: 5 MB
- Поддерживаемые форматы: JPEG, PNG, GIF, WebP

### Безопасность
- CSRF защита включена для всех POST/PUT/DELETE запросов
- Rate limiting для API endpoints
- Пароли хэшируются с помощью bcrypt
- XSS защита через Blade escaping

### Логирование
- Канал: stack (single file)
- Уровень: debug (в production рекомендуется error)
- Логи сохраняются в `storage/logs/laravel.log`

## Разработка

### Компиляция ассетов

```bash
# Development mode с hot reload
npm run dev

# Production build
npm run build
```

### Очистка кэша

```bash
# Очистка всех кэшей
php artisan optimize:clear

# Очистка конфигурации
php artisan config:clear

# Очистка маршрутов
php artisan route:clear

# Очистка views
php artisan view:clear
```

### Создание нового администратора

```bash
php artisan tinker

# В tinker:
User::create([
    'name' => 'Admin Name',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'role' => 'admin'
]);
```

## Production Deployment

### 1. Оптимизация

```bash
# Кэширование конфигурации
php artisan config:cache

# Кэширование маршрутов
php artisan route:cache

# Кэширование views
php artisan view:cache

# Компиляция ассетов
npm run build
```

### 2. Настройка .env для production

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

LOG_LEVEL=error

# Используйте безопасные настройки сессии
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
```

### 3. Права доступа

```bash
# Установка правильных прав
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## Устранение неполадок

### Ошибка "No application encryption key"
```bash
php artisan key:generate
```

### Ошибка "Class not found"
```bash
composer dump-autoload
```

### Ошибка подключения к базе данных
- Проверьте параметры в `.env`
- Убедитесь, что MariaDB запущен
- Проверьте права пользователя БД

### Изображения не отображаются
```bash
php artisan storage:link
```

### Ошибки миграций
```bash
# Откат всех миграций и повторный запуск
php artisan migrate:fresh --seed
```

## Лицензия

Этот проект использует Laravel framework, который распространяется под лицензией MIT.

## Поддержка

Для вопросов и поддержки создайте issue в репозитории проекта.
