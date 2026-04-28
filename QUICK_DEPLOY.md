# ⚡ Быстрое развертывание проекта "Флора"

## 🎯 Для тех, кто спешит

### Предварительные требования
✅ PHP 8.2+  
✅ Composer  
✅ Node.js 18+  
✅ MariaDB/MySQL  
✅ Git  

---

## 🚀 5 шагов до запуска

### 1️⃣ Клонирование и зависимости
```bash
git clone <URL_РЕПОЗИТОРИЯ>
cd FlowerKursachLaravel
composer install
npm install
```

### 2️⃣ Настройка окружения
```bash
cp .env.example .env
php artisan key:generate
```

Отредактируйте `.env`:
```env
DB_DATABASE=flowershopdb
DB_USERNAME=root
DB_PASSWORD=ваш_пароль
```

### 3️⃣ База данных
```bash
# Создайте БД в MySQL
mysql -u root -p
CREATE DATABASE flowershopdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Миграции и данные
php artisan migrate
php artisan db:seed
php artisan storage:link
```

### 4️⃣ API документация
```bash
# Windows
.\fix-openapi.ps1

# Linux/Mac
bash fix-openapi.sh
```

### 5️⃣ Запуск
```bash
# Терминал 1
php artisan serve

# Терминал 2
npm run dev
```

Откройте: **http://localhost:8000**

---

## 🔐 Вход

**Администратор:**
- Email: `sonab2412@gmail.com`
- Пароль: `13211321`

---

## 🐛 Проблемы?

```bash
# Очистка кэша
php artisan optimize:clear

# Проверка подключения к БД
php artisan migrate:status

# Просмотр логов
tail -f storage/logs/laravel.log
```

**Полная инструкция:** [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)

---

## ✅ Чеклист

- [ ] `composer install` ✓
- [ ] `npm install` ✓
- [ ] `.env` настроен ✓
- [ ] БД создана ✓
- [ ] `php artisan migrate` ✓
- [ ] `php artisan db:seed` ✓
- [ ] `php artisan storage:link` ✓
- [ ] Сервер запущен ✓
- [ ] Vite запущен ✓

**Готово!** 🎉
