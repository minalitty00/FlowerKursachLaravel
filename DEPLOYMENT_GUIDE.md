# 🚀 Инструкция по развертыванию проекта "Флора" на другом компьютере

## 📋 Содержание
1. [Требования к системе](#требования-к-системе)
2. [Установка необходимого ПО](#установка-необходимого-по)
3. [Клонирование проекта](#клонирование-проекта)
4. [Настройка проекта](#настройка-проекта)
5. [Запуск проекта](#запуск-проекта)
6. [Решение проблем](#решение-проблем)

---

## 📦 Требования к системе

### Минимальные требования:
- **ОС**: Windows 10/11, macOS 10.15+, или Linux (Ubuntu 20.04+)
- **RAM**: 4 GB (рекомендуется 8 GB)
- **Диск**: 2 GB свободного места
- **Интернет**: для установки зависимостей

### Необходимое ПО:
- PHP 8.2 или выше
- Composer 2.0+
- Node.js 18.x или выше
- npm (идет с Node.js)
- MariaDB 10.6+ или MySQL 8.0+
- Git

---

## 🔧 Установка необходимого ПО

### Windows

#### 1. PHP
Скачайте и установите:
- **Вариант 1**: [XAMPP](https://www.apachefriends.org/download.html) (включает PHP, MariaDB, Apache)
- **Вариант 2**: [PHP для Windows](https://windows.php.net/download/)

После установки проверьте:
```powershell
php -v
```

#### 2. Composer
1. Скачайте: https://getcomposer.org/Composer-Setup.exe
2. Запустите установщик
3. Проверьте:
```powershell
composer -V
```

#### 3. Node.js и npm
1. Скачайте: https://nodejs.org/ (LTS версия)
2. Запустите установщик
3. Проверьте:
```powershell
node -v
npm -v
```

#### 4. MariaDB
1. Скачайте: https://mariadb.org/download/
2. Запустите установщик
3. Запомните пароль root!
4. Проверьте:
```powershell
mysql -V
```

#### 5. Git
1. Скачайте: https://git-scm.com/download/win
2. Запустите установщик
3. Проверьте:
```powershell
git --version
```

### Linux (Ubuntu/Debian)

```bash
# Обновление системы
sudo apt update && sudo apt upgrade -y

# PHP и расширения
sudo apt install php8.2 php8.2-cli php8.2-common php8.2-mysql php8.2-xml php8.2-curl php8.2-mbstring php8.2-zip php8.2-bcmath -y

# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Node.js и npm
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs -y

# MariaDB
sudo apt install mariadb-server -y
sudo mysql_secure_installation

# Git
sudo apt install git -y
```

### macOS

```bash
# Homebrew (если еще не установлен)
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# PHP
brew install php@8.2

# Composer
brew install composer

# Node.js
brew install node@18

# MariaDB
brew install mariadb
brew services start mariadb

# Git
brew install git
```

---

## 📥 Клонирование проекта

### Вариант 1: Через Git (если проект в репозитории)

```bash
# Клонирование репозитория
git clone <URL_РЕПОЗИТОРИЯ>
cd FlowerKursachLaravel
```

### Вариант 2: Копирование файлов

1. Скопируйте всю папку проекта на новый компьютер
2. Откройте терминал в папке проекта

---

## ⚙️ Настройка проекта

### Шаг 1: Установка зависимостей PHP

```bash
composer install
```

**Если ошибка "composer not found":**
```bash
php composer.phar install
```

### Шаг 2: Установка зависимостей Node.js

```bash
npm install
```

### Шаг 3: Настройка файла окружения

```bash
# Копирование файла конфигурации
cp .env.example .env

# Генерация ключа приложения
php artisan key:generate
```

### Шаг 4: Настройка базы данных

#### 4.1. Создание базы данных

**Windows (через XAMPP/phpMyAdmin):**
1. Откройте http://localhost/phpmyadmin
2. Создайте новую БД: `flowershopdb`
3. Кодировка: `utf8mb4_unicode_ci`

**Через командную строку:**
```bash
# Войдите в MariaDB
mysql -u root -p

# Создайте базу данных
CREATE DATABASE flowershopdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

#### 4.2. Настройка .env файла

Откройте файл `.env` и измените:

```env
APP_NAME="Флора"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

APP_LOCALE=ru
APP_FALLBACK_LOCALE=ru
APP_FAKER_LOCALE=ru_RU

# Настройки базы данных
DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=flowershopdb
DB_USERNAME=root
DB_PASSWORD=ваш_пароль_от_mysql

# Сессии
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

**Важно:** Замените `ваш_пароль_от_mysql` на реальный пароль!

### Шаг 5: Миграции и начальные данные

```bash
# Запуск миграций
php artisan migrate

# Заполнение базы данных
php artisan db:seed
```

Это создаст:
- Администратора: `sonab2412@gmail.com` / `13211321`
- Категории товаров
- Тестовые данные

### Шаг 6: Настройка хранилища файлов

```bash
# Создание символической ссылки
php artisan storage:link
```

### Шаг 7: Очистка кэша

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Шаг 8: Генерация API документации

**Windows:**
```powershell
.\fix-openapi.ps1
```

**Linux/Mac:**
```bash
chmod +x fix-openapi.sh
./fix-openapi.sh
```

**Или вручную:**
```bash
php artisan spectrum:generate --output=public/openapi.json
```

---

## 🚀 Запуск проекта

### Вариант 1: Два терминала (рекомендуется для разработки)

**Терминал 1 - Laravel сервер:**
```bash
php artisan serve
```

**Терминал 2 - Vite dev server:**
```bash
npm run dev
```

Откройте браузер: http://localhost:8000

### Вариант 2: Production build

```bash
# Компиляция ассетов
npm run build

# Запуск сервера
php artisan serve
```

### Вариант 3: Через XAMPP/Apache

1. Скопируйте проект в `C:\xampp\htdocs\flower-shop`
2. Настройте виртуальный хост (опционально)
3. Откройте: http://localhost/flower-shop/public

---

## 🔐 Тестовые учетные данные

### Администратор
- **Email**: `sonab2412@gmail.com`
- **Пароль**: `13211321`

### Обычный пользователь
Зарегистрируйтесь через форму на сайте

---

## 📖 Доступные страницы

После запуска доступны:

- **Главная**: http://localhost:8000/
- **Товары**: http://localhost:8000/products
- **Корзина**: http://localhost:8000/cart
- **Админ-панель**: http://localhost:8000/admin/dashboard
- **API Документация**: http://localhost:8000/api-docs
- **Тест API**: http://localhost:8000/test-api-docs.html

---

## 🐛 Решение проблем

### Проблема 1: "Class not found"

```bash
composer dump-autoload
php artisan clear-compiled
php artisan optimize:clear
```

### Проблема 2: "No application encryption key"

```bash
php artisan key:generate
```

### Проблема 3: Ошибка подключения к БД

1. Проверьте что MariaDB запущен:
```bash
# Windows
net start MySQL

# Linux
sudo systemctl start mariadb
```

2. Проверьте настройки в `.env`:
   - `DB_HOST=127.0.0.1` (не localhost!)
   - Правильный пароль
   - База данных создана

3. Проверьте подключение:
```bash
mysql -u root -p
```

### Проблема 4: Ошибка миграций

```bash
# Откат и повторный запуск
php artisan migrate:fresh --seed
```

**Внимание:** Это удалит все данные!

### Проблема 5: Изображения не отображаются

```bash
php artisan storage:link
```

Проверьте что папка `public/storage` существует.

### Проблема 6: Ошибка 500 на странице

1. Включите отладку в `.env`:
```env
APP_DEBUG=true
```

2. Проверьте логи:
```
storage/logs/laravel.log
```

3. Проверьте права доступа:
```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Windows - запустите терминал от администратора
```

### Проблема 7: npm install не работает

```bash
# Очистка кэша npm
npm cache clean --force

# Удаление node_modules
rm -rf node_modules
rm package-lock.json

# Повторная установка
npm install
```

### Проблема 8: Vite не запускается

```bash
# Проверьте версию Node.js (должна быть 18+)
node -v

# Переустановите зависимости
npm install

# Запустите с флагом host
npm run dev -- --host
```

### Проблема 9: API документация показывает 404

1. Проверьте что файл существует:
```bash
ls public/openapi.json
```

2. Регенерируйте документацию:
```bash
php artisan spectrum:generate --output=public/openapi.json
```

3. Очистите кэш:
```bash
php artisan route:clear
php artisan view:clear
```

4. Перезапустите сервер

### Проблема 10: CSRF ошибка 419 в API документации

Это нормально для Swagger UI при тестировании через браузер. API работает корректно при использовании из приложений (Postman, curl, JavaScript fetch).

Для тестирования через Swagger UI:
1. Сначала войдите на сайт через браузер
2. Затем используйте Swagger UI в той же сессии

---

## 📝 Дополнительные команды

### Очистка всех кэшей
```bash
php artisan optimize:clear
```

### Создание нового администратора
```bash
php artisan tinker

# В tinker:
User::create([
    'name' => 'Имя',
    'email' => 'email@example.com',
    'password' => bcrypt('пароль'),
    'role' => 'admin'
]);
exit
```

### Запуск тестов
```bash
php artisan test
```

### Компиляция для production
```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📚 Полезные ссылки

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Spectrum](https://github.com/wadakatu/laravel-spectrum)
- [Vite Documentation](https://vitejs.dev/)
- [MariaDB Documentation](https://mariadb.org/documentation/)

---

## ✅ Чеклист развертывания

- [ ] Установлено все необходимое ПО (PHP, Composer, Node.js, MariaDB, Git)
- [ ] Проект скопирован/клонирован
- [ ] Выполнен `composer install`
- [ ] Выполнен `npm install`
- [ ] Создан файл `.env` из `.env.example`
- [ ] Сгенерирован ключ приложения (`php artisan key:generate`)
- [ ] Создана база данных `flowershopdb`
- [ ] Настроены параметры БД в `.env`
- [ ] Выполнены миграции (`php artisan migrate`)
- [ ] Загружены начальные данные (`php artisan db:seed`)
- [ ] Создана символическая ссылка (`php artisan storage:link`)
- [ ] Очищен кэш (`php artisan optimize:clear`)
- [ ] Сгенерирована API документация
- [ ] Запущен Laravel сервер (`php artisan serve`)
- [ ] Запущен Vite dev server (`npm run dev`)
- [ ] Открыт браузер на http://localhost:8000
- [ ] Выполнен вход под администратором
- [ ] Проверена работа основных функций

---

## 🎉 Готово!

Если все шаги выполнены, проект должен работать!

При возникновении проблем:
1. Проверьте логи: `storage/logs/laravel.log`
2. Включите отладку: `APP_DEBUG=true` в `.env`
3. Проверьте что все сервисы запущены (MariaDB, PHP, npm)

**Удачи!** 🚀
