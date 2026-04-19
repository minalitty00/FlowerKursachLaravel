# Инструкции по завершению локализации

## Выполненные изменения:

1. ✅ Создан helper для форматирования цен в рублях (`app/Helpers/helpers.php`)
2. ✅ Добавлен autoload для helpers в `composer.json`
3. ✅ Создан `lang/ru/auth.php` с русскими сообщениями аутентификации
4. ✅ Создан `lang/ru/passwords.php` с русскими сообщениями для паролей
5. ✅ Переведены: `home.blade.php`, `layouts/app.blade.php`, `auth/login.blade.php`, `auth/register.blade.php`, `products/index.blade.php`
6. ✅ Настроен часовой пояс `Asia/Vladivostok` в `config/app.php`
7. ✅ Настроена локаль `ru` в `config/app.php`

## Необходимо выполнить:

### 1. Обновить autoload composer
```bash
composer dump-autoload
```

### 2. Очистить кэш Laravel
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### 3. Файлы, которые еще нужно перевести:

#### Страницы товаров:
- `resources/views/products/show.blade.php` - заменить `${{ number_format($product->price, 2) }}` на `{{ format_price($product->price) }}`

#### Корзина:
- `resources/views/cart/index.blade.php` - перевести текст и заменить цены

#### Заказы:
- `resources/views/orders/index.blade.php` - перевести текст и заменить цены
- `resources/views/orders/show.blade.php` - перевести текст и заменить цены
- `resources/views/orders/checkout.blade.php` - перевести текст

#### Админ-панель:
- `resources/views/layouts/admin.blade.php` - перевести навигацию
- `resources/views/admin/dashboard.blade.php` - перевести текст
- `resources/views/admin/products/index.blade.php` - перевести текст и заменить цены
- `resources/views/admin/products/create.blade.php` - перевести формы
- `resources/views/admin/products/edit.blade.php` - перевести формы
- `resources/views/admin/orders/index.blade.php` - перевести текст
- `resources/views/admin/orders/show.blade.php` - перевести текст и заменить цены
- `resources/views/admin/categories/index.blade.php` - перевести текст
- `resources/views/admin/revenue.blade.php` - перевести текст и заменить цены

### 4. Замена цен

Найти и заменить во всех файлах:
- `${{ number_format($product->price, 2) }}` → `{{ format_price($product->price) }}`
- `${{ number_format($item->price, 2) }}` → `{{ format_price($item->price) }}`
- `${{ number_format($item->subtotal, 2) }}` → `{{ format_price($item->subtotal) }}`
- `${{ number_format($order->total, 2) }}` → `{{ format_price($order->total) }}`

### 5. Общие переводы

Английский → Русский:
- "Home" → "Главная"
- "Products" → "Товары"
- "Cart" → "Корзина"
- "My Orders" → "Мои заказы"
- "Admin Panel" → "Админ-панель"
- "Login" → "Вход"
- "Logout" → "Выход"
- "Register" → "Регистрация"
- "Search" → "Найти" / "Поиск"
- "All Categories" → "Все категории"
- "In stock" → "В наличии"
- "Add to Cart" → "Добавить в корзину"
- "Checkout" → "Оформить заказ"
- "Order" → "Заказ"
- "Total" → "Итого"
- "Status" → "Статус"
- "Date" → "Дата"
- "Price" → "Цена"
- "Quantity" → "Количество"
- "Name" → "Название" / "Имя"
- "Description" → "Описание"
- "Category" → "Категория"
- "Image" → "Изображение"
- "Actions" → "Действия"
- "Edit" → "Редактировать"
- "Delete" → "Удалить"
- "Create" → "Создать"
- "Update" → "Обновить"
- "Save" → "Сохранить"
- "Cancel" → "Отменить"
- "Back" → "Назад"
- "Dashboard" → "Панель управления"
- "Revenue" → "Выручка"
- "Orders" → "Заказы"
- "Categories" → "Категории"

### 6. Форматирование дат

В файлах где используется `->format('M d, Y')` заменить на `->format('d.m.Y')` для русского формата даты.

## Проверка

После выполнения всех шагов:
1. Перезапустите сервер Laravel (`php artisan serve`)
2. Перезапустите Vite (`npm run dev`)
3. Откройте сайт и проверьте:
   - Все тексты на русском
   - Цены отображаются в рублях (₽)
   - Даты в формате дд.мм.гггг
   - Время соответствует часовому поясу Владивостока
