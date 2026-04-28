# ❓ Часто задаваемые вопросы (FAQ)

## 📋 Содержание
- [Установка и настройка](#установка-и-настройка)
- [База данных](#база-данных)
- [API и документация](#api-и-документация)
- [Разработка](#разработка)
- [Production](#production)

---

## Установка и настройка

### ❓ Какая версия PHP нужна?
**Ответ:** PHP 8.2 или выше. Проверьте версию:
```bash
php -v
```

### ❓ Как установить все зависимости одной командой?
**Ответ:**
```bash
composer install && npm install
```

### ❓ Что делать если composer install выдает ошибку?
**Ответ:**
```bash
# Обновите composer
composer self-update

# Очистите кэш
composer clear-cache

# Попробуйте снова
composer install --no-cache
```

### ❓ Нужен ли мне Apache/Nginx?
**Ответ:** Нет, для разработки достаточно встроенного сервера Laravel:
```bash
php artisan serve
```

Для production рекомендуется Nginx или Apache.

---

## База данных

### ❓ Какую БД использовать - MySQL или MariaDB?
**Ответ:** Оба варианта работают. MariaDB - это форк MySQL с улучшенной производительностью. Проект изначально разработан для MariaDB, но полностью совместим с MySQL 8.0+.

### ❓ Как сбросить базу данных?
**Ответ:**
```bash
php artisan migrate:fresh --seed
```
**Внимание:** Это удалит все данные!

### ❓ Как создать нового администратора?
**Ответ:**
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

### ❓ Забыл пароль администратора, что делать?
**Ответ:**
```bash
php artisan tinker

$user = User::where('email', 'sonab2412@gmail.com')->first();
$user->password = bcrypt('новый_пароль');
$user->save();
exit
```

### ❓ Ошибка "SQLSTATE[HY000] [2002] Connection refused"
**Ответ:**
1. Проверьте что MariaDB запущен
2. В `.env` используйте `DB_HOST=127.0.0.1` вместо `localhost`
3. Проверьте порт (обычно 3306)

---

## API и документация

### ❓ Где посмотреть API документацию?
**Ответ:** http://localhost:8000/api-docs

### ❓ Почему в Swagger UI ошибка 419?
**Ответ:** Это нормально! Это CSRF защита Laravel. Решения:
1. Войдите на сайт, затем используйте Swagger UI
2. Используйте Postman для тестирования API
3. Читайте: [API_DOCS_FIX.md](API_DOCS_FIX.md)

### ❓ Как обновить API документацию после изменений?
**Ответ:**
```bash
# Windows
.\fix-openapi.ps1

# Linux/Mac
bash fix-openapi.sh
```

### ❓ Можно ли использовать API без веб-интерфейса?
**Ответ:** Да! API полностью независим. Используйте любой HTTP клиент (Postman, curl, fetch, axios).

### ❓ Как получить список всех API endpoints?
**Ответ:**
```bash
php artisan route:list | grep api
```

---

## Разработка

### ❓ Как запустить проект для разработки?
**Ответ:** Откройте два терминала:
```bash
# Терминал 1
php artisan serve

# Терминал 2
npm run dev
```

### ❓ Vite не запускается, что делать?
**Ответ:**
```bash
# Проверьте версию Node.js (нужна 18+)
node -v

# Переустановите зависимости
rm -rf node_modules package-lock.json
npm install

# Попробуйте с флагом host
npm run dev -- --host
```

### ❓ Как очистить все кэши?
**Ответ:**
```bash
php artisan optimize:clear
```

Или по отдельности:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### ❓ Изображения не отображаются
**Ответ:**
```bash
php artisan storage:link
```

Проверьте что `public/storage` существует и является символической ссылкой.

### ❓ Как запустить тесты?
**Ответ:**
```bash
php artisan test
```

### ❓ Где смотреть логи ошибок?
**Ответ:**
```
storage/logs/laravel.log
```

Или в реальном времени:
```bash
# Linux/Mac
tail -f storage/logs/laravel.log

# Windows PowerShell
Get-Content storage/logs/laravel.log -Wait -Tail 50
```

---

## Production

### ❓ Как подготовить проект к production?
**Ответ:**
```bash
# 1. Компиляция ассетов
npm run build

# 2. Кэширование конфигурации
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Оптимизация autoload
composer install --optimize-autoloader --no-dev

# 4. Настройка .env
APP_ENV=production
APP_DEBUG=false
```

### ❓ Какие настройки изменить в .env для production?
**Ответ:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ваш-домен.com

# Безопасные сессии
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict

# Логирование
LOG_LEVEL=error
```

### ❓ Как настроить Nginx для Laravel?
**Ответ:** Пример конфигурации:
```nginx
server {
    listen 80;
    server_name ваш-домен.com;
    root /path/to/project/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### ❓ Нужно ли загружать node_modules на сервер?
**Ответ:** Нет! Скомпилируйте ассеты локально (`npm run build`) и загрузите только папку `public/build`.

### ❓ Какие папки нужны права на запись?
**Ответ:**
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## Общие вопросы

### ❓ Можно ли использовать проект коммерчески?
**Ответ:** Да, проект использует MIT лицензию Laravel.

### ❓ Как добавить новую категорию товаров?
**Ответ:** Через админ-панель:
1. Войдите как администратор
2. Перейдите в "Категории"
3. Нажмите "Добавить категорию"

Или через API:
```bash
curl -X POST http://localhost:8000/api/categories \
  -H "Content-Type: application/json" \
  -d '{"name": "Новая категория"}'
```

### ❓ Как изменить название магазина?
**Ответ:** В файле `.env`:
```env
APP_NAME="Ваше название"
```

Затем:
```bash
php artisan config:clear
```

### ❓ Как изменить цветовую схему?
**Ответ:** Основные цвета в `resources/views/layouts/app.blade.php`:
- Фон: `#60d18a` (мятный)
- Акцент: `#c94b8c` (розовый)
- Футер: `#c94b8c`

### ❓ Поддерживается ли мультиязычность?
**Ответ:** Сейчас только русский язык. Для добавления других языков:
1. Создайте папку `lang/en` (для английского)
2. Скопируйте файлы из `lang/ru`
3. Переведите тексты
4. Измените `APP_LOCALE` в `.env`

### ❓ Как добавить новый способ оплаты?
**Ответ:** Нужно:
1. Создать миграцию для добавления поля `payment_method` в таблицу `orders`
2. Обновить `OrderService` для обработки оплаты
3. Добавить форму выбора способа оплаты в `checkout.blade.php`

### ❓ Есть ли мобильное приложение?
**Ответ:** Нет, но API готов для использования в мобильных приложениях. Можно разработать приложение на React Native, Flutter или нативно.

### ❓ Как настроить email уведомления?
**Ответ:** В `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=ваш_email@gmail.com
MAIL_PASSWORD=ваш_пароль_приложения
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=ваш_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### ❓ Где хранятся изображения товаров?
**Ответ:** В `storage/app/public/products/`. Доступны через `public/storage/products/` после выполнения `php artisan storage:link`.

---

## 🆘 Не нашли ответ?

1. Проверьте [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)
2. Проверьте [API_DOCS_FIX.md](API_DOCS_FIX.md)
3. Посмотрите логи: `storage/logs/laravel.log`
4. Включите отладку: `APP_DEBUG=true` в `.env`

---

**Последнее обновление:** 2026-04-28
