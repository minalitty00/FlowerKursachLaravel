# Laravel Spectrum - Руководство по использованию

## Что такое Laravel Spectrum?

Laravel Spectrum - это пакет для автоматической генерации API документации в формате OpenAPI 3.0 без необходимости писать аннотации. Пакет анализирует ваш код и автоматически создает полную документацию API.

## Установка

Пакет уже установлен в проекте. Если нужно установить в другой проект:

```bash
composer require wadakatu/laravel-spectrum --dev
php artisan vendor:publish --tag=spectrum-config
```

## Основные команды

### Генерация документации

```bash
# Генерация в JSON формате (по умолчанию)
php artisan spectrum:generate

# Генерация в YAML формате
php artisan spectrum:generate --format=yaml

# Указание пользовательского пути
php artisan spectrum:generate --output=public/my-api-docs.json
```

После генерации файл будет сохранен в `storage/app/spectrum/openapi.json`

### Режим разработки с автообновлением

```bash
# Запуск watcher
php artisan spectrum:watch

# Документация будет доступна на http://localhost:8080
# И будет автоматически обновляться при изменении кода
```

## Просмотр документации

В этом проекте документация доступна по адресу:
- **Swagger UI**: http://localhost:8000/api-docs

## Конфигурация

Файл конфигурации находится в `config/spectrum.php`

### Основные настройки

```php
// Название API
'title' => env('APP_NAME', 'Laravel').' API',

// Версия API
'version' => '1.0.0',

// Паттерны маршрутов для включения в документацию
'route_patterns' => [
    'api/*',
],

// Исключенные маршруты
'excluded_routes' => [
    'api/health',
    'api/ping',
],

// Теги для группировки endpoints
'tags' => [
    'api/register' => 'Authentication',
    'api/login' => 'Authentication',
    'api/categories*' => 'Categories',
    'api/products*' => 'Products',
    'api/cart*' => 'Cart',
    'api/orders*' => 'Orders',
],
```

### Настройка аутентификации

```php
'authentication' => [
    'global' => [
        'enabled' => true,
        'scheme' => [
            'type' => 'http',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT',
        ],
    ],
],
```

### Кэширование

```php
'cache' => [
    'enabled' => true,
    'directory' => storage_path('app/spectrum/cache'),
    'ttl' => null, // Без истечения
],
```

## Что автоматически определяется

### 1. Валидация запросов

Laravel Spectrum автоматически анализирует:
- **FormRequest классы** - все правила валидации
- **Inline валидация** - правила в контроллерах
- **Типы полей** - string, integer, boolean, array и т.д.
- **Обязательные поля** - required правила
- **Enum значения** - in:value1,value2

Пример:
```php
// В FormRequest
public function rules()
{
    return [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'age' => 'integer|min:18|max:100',
        'status' => 'in:active,inactive',
    ];
}
```

Spectrum автоматически создаст схему с:
- Обязательными полями (name, email)
- Типами данных (string, integer)
- Ограничениями (maxLength, minimum, maximum)
- Enum значениями (active, inactive)

### 2. Структуры ответов

Автоматически определяет:
- **API Resources** - Laravel Resource классы
- **Fractal Transformers** - если используются
- **Пагинация** - Laravel pagination
- **Вложенные ресурсы** - relationships

### 3. Параметры запросов

Определяет:
- **Query параметры** - из $request->query()
- **Path параметры** - из route parameters
- **File uploads** - из правил валидации file/image

### 4. Коды ответов

Автоматически документирует:
- 200 - Успешный ответ
- 201 - Создано
- 401 - Не авторизован
- 403 - Доступ запрещен
- 404 - Не найдено
- 422 - Ошибка валидации
- 500 - Ошибка сервера

## Примеры использования

### Пример 1: Простой CRUD endpoint

```php
// Controller
public function store(StoreProductRequest $request)
{
    $product = Product::create($request->validated());
    return response()->json($product, 201);
}

// FormRequest
class StoreProductRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:5120',
        ];
    }
}
```

Spectrum автоматически создаст документацию с:
- Всеми полями и их типами
- Правилами валидации
- Обработкой загрузки файлов
- Кодами ответов 201 и 422

### Пример 2: Endpoint с фильтрацией

```php
public function index(Request $request)
{
    $query = Product::query();
    
    if ($request->has('category_id')) {
        $query->where('category_id', $request->category_id);
    }
    
    if ($request->has('search')) {
        $query->where('name', 'like', '%'.$request->search.'%');
    }
    
    return $query->paginate(15);
}
```

Spectrum определит query параметры:
- category_id (integer)
- search (string)
- page (integer) - из пагинации
- per_page (integer) - из пагинации

### Пример 3: API Resource

```php
// Controller
public function show($id)
{
    $product = Product::findOrFail($id);
    return new ProductResource($product);
}

// Resource
class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'category' => new CategoryResource($this->category),
            'created_at' => $this->created_at->toISOString(),
        ];
    }
}
```

Spectrum создаст полную схему ответа с вложенными ресурсами.

## Обновление документации

### После изменения кода

```bash
# Просто запустите генерацию заново
php artisan spectrum:generate

# Или используйте watch mode для автоматического обновления
php artisan spectrum:watch
```

### Очистка кэша

```bash
# Удалить кэш Spectrum
rm -rf storage/app/spectrum/cache

# Или отключить кэш в config/spectrum.php
'cache' => [
    'enabled' => false,
],
```

## Интеграция с CI/CD

### GitHub Actions

```yaml
- name: Generate API Documentation
  run: |
    php artisan spectrum:generate
    cp storage/app/spectrum/openapi.json public/openapi.json
```

### GitLab CI

```yaml
generate-docs:
  script:
    - php artisan spectrum:generate
    - cp storage/app/spectrum/openapi.json public/openapi.json
  artifacts:
    paths:
      - public/openapi.json
```

## Советы и лучшие практики

### 1. Используйте FormRequest классы

Вместо inline валидации используйте FormRequest - Spectrum лучше их анализирует:

```php
// ✅ Хорошо
public function store(StoreProductRequest $request)
{
    // ...
}

// ❌ Плохо (но тоже работает)
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string',
    ]);
}
```

### 2. Документируйте сложные ответы через Resources

```php
// ✅ Хорошо - Spectrum видит структуру
return new ProductResource($product);

// ❌ Плохо - Spectrum не может определить структуру
return response()->json([
    'data' => $product->toArray(),
]);
```

### 3. Используйте теги для группировки

В `config/spectrum.php`:

```php
'tags' => [
    'api/auth/*' => 'Authentication',
    'api/products/*' => 'Products',
    'api/orders/*' => 'Orders',
],
```

### 4. Настройте аутентификацию

Если используете Sanctum или JWT:

```php
'authentication' => [
    'global' => [
        'enabled' => true,
        'scheme' => [
            'type' => 'http',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT',
        ],
    ],
],
```

## Устранение неполадок

### Маршруты не появляются в документации

Проверьте `route_patterns` в config:

```php
'route_patterns' => [
    'api/*', // Убедитесь, что паттерн соответствует вашим маршрутам
],
```

### Валидация не определяется

- Убедитесь, что FormRequest правильно type-hinted
- Проверьте, что метод `rules()` возвращает массив
- Очистите кэш: `rm -rf storage/app/spectrum/cache`

### Документация не обновляется

```bash
# Очистите кэш
rm -rf storage/app/spectrum/cache

# Регенерируйте
php artisan spectrum:generate
```

## Полезные ссылки

- [GitHub репозиторий](https://github.com/wadakatu/laravel-spectrum)
- [Документация](https://github.com/wadakatu/laravel-spectrum/tree/main/docs)
- [OpenAPI Specification](https://swagger.io/specification/)
- [Swagger UI](https://swagger.io/tools/swagger-ui/)

## Заключение

Laravel Spectrum значительно упрощает создание и поддержку API документации. Основные преимущества:

- ✅ Нет необходимости писать аннотации
- ✅ Документация всегда актуальна
- ✅ Автоматическое определение валидации
- ✅ Поддержка API Resources
- ✅ Режим разработки с hot reload
- ✅ Умное кэширование

Просто пишите код по стандартам Laravel, и Spectrum создаст документацию автоматически!
