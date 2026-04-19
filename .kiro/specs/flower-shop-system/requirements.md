# Requirements Document

## Introduction

Система веб-сайта цветочного магазина предоставляет полнофункциональную платформу электронной коммерции для продажи цветов. Система включает RESTful API на Laravel, клиентское веб-приложение на Blade и административную панель с разделением ролей пользователей.

## Glossary

- **Flower_Shop_System**: Полная система веб-сайта цветочного магазина
- **API**: RESTful API на Laravel для серверной логики
- **Client_Application**: Клиентское веб-приложение на Laravel Blade
- **Admin_Panel**: Административная панель управления
- **User**: Обычный пользователь системы (покупатель)
- **Administrator**: Пользователь с административными правами
- **Product**: Товар (цветы или букеты) в каталоге магазина
- **Order**: Заказ, созданный пользователем
- **Revenue_Report**: Отчет о выручке по месяцам
- **Database**: База данных MariaDB
- **Authentication_System**: Система аутентификации пользователей
- **Cart**: Корзина покупок пользователя
- **Category**: Категория товаров

## Requirements

### Requirement 1: User Authentication and Authorization

**User Story:** Как пользователь системы, я хочу иметь возможность регистрации и входа в систему, чтобы получить доступ к функциям в соответствии с моей ролью.

#### Acceptance Criteria

1. THE Authentication_System SHALL support two user roles: User and Administrator
2. WHEN a new user registers, THE Authentication_System SHALL create a User account with default User role
3. WHEN a user provides valid credentials, THE Authentication_System SHALL authenticate the user and create a session
4. WHEN a user provides invalid credentials, THE Authentication_System SHALL return an authentication error
5. THE Authentication_System SHALL hash all passwords before storing them in the Database
6. WHEN an unauthenticated user attempts to access protected resources, THE API SHALL return HTTP 401 Unauthorized status

### Requirement 2: Product Catalog Management

**User Story:** Как администратор, я хочу управлять каталогом товаров, чтобы поддерживать актуальный ассортимент магазина.

#### Acceptance Criteria

1. WHEN an Administrator creates a product, THE API SHALL store the product with name, description, price, image, category, and stock quantity in the Database
2. WHEN an Administrator updates a product, THE API SHALL modify the existing product data in the Database
3. WHEN an Administrator deletes a product, THE API SHALL remove the product from the Database
4. THE API SHALL validate that product price is a positive decimal number
5. THE API SHALL validate that stock quantity is a non-negative integer
6. WHEN a User requests the product catalog, THE API SHALL return all products with stock quantity greater than zero
7. THE API SHALL support product image upload and storage in the file system

### Requirement 3: Product Browsing and Search

**User Story:** Как пользователь, я хочу просматривать и искать товары, чтобы найти нужные цветы для покупки.

#### Acceptance Criteria

1. WHEN a User requests the product list, THE Client_Application SHALL display all available products with images, names, and prices
2. THE Client_Application SHALL support filtering products by category
3. THE Client_Application SHALL support searching products by name
4. WHEN a User clicks on a product, THE Client_Application SHALL display detailed product information including description and stock availability
5. THE API SHALL return products sorted by creation date in descending order by default

### Requirement 4: Shopping Cart Management

**User Story:** Как пользователь, я хочу добавлять товары в корзину, чтобы оформить заказ на несколько товаров одновременно.

#### Acceptance Criteria

1. WHEN a User adds a product to cart, THE Client_Application SHALL store the product and quantity in the user's session
2. WHEN a User updates product quantity in cart, THE Client_Application SHALL validate that quantity does not exceed available stock
3. WHEN a User removes a product from cart, THE Client_Application SHALL delete the product from the cart
4. THE Client_Application SHALL display the total price of all items in the cart
5. WHEN a User views the cart, THE Client_Application SHALL display all cart items with product details, quantities, and subtotals

### Requirement 5: Order Processing

**User Story:** Как пользователь, я хочу оформлять заказы, чтобы приобрести выбранные товары.

#### Acceptance Criteria

1. WHEN a User submits an order, THE API SHALL create an Order record with user information, order items, total amount, and timestamp
2. WHEN an order is created, THE API SHALL reduce the stock quantity of ordered products
3. IF product stock is insufficient, THEN THE API SHALL return an error and prevent order creation
4. THE API SHALL assign a unique order number to each order
5. WHEN an order is created, THE API SHALL set the initial order status to "pending"
6. THE API SHALL validate that the cart is not empty before creating an order
7. WHEN an order is successfully created, THE API SHALL clear the user's cart

### Requirement 6: Order Management for Users

**User Story:** Как пользователь, я хочу просматривать историю своих заказов, чтобы отслеживать покупки.

#### Acceptance Criteria

1. WHEN a User requests their orders, THE API SHALL return all orders belonging to that user
2. THE Client_Application SHALL display order history with order number, date, total amount, and status
3. WHEN a User views order details, THE Client_Application SHALL display all order items with product names, quantities, and prices
4. THE API SHALL return orders sorted by creation date in descending order

### Requirement 7: Administrative Order Management

**User Story:** Как администратор, я хочу управлять всеми заказами, чтобы обрабатывать и отслеживать продажи.

#### Acceptance Criteria

1. WHEN an Administrator requests orders, THE API SHALL return all orders from all users
2. THE Admin_Panel SHALL display all orders with order number, customer name, date, total amount, and status
3. WHEN an Administrator updates order status, THE API SHALL modify the order status in the Database
4. THE API SHALL support the following order statuses: "pending", "processing", "completed", "cancelled"
5. WHEN an Administrator views order details, THE Admin_Panel SHALL display complete order information including customer details and order items

### Requirement 8: Revenue Reporting

**User Story:** Как администратор, я хочу просматривать отчеты о выручке по месяцам, чтобы анализировать финансовые показатели магазина.

#### Acceptance Criteria

1. WHEN an Administrator requests revenue report, THE API SHALL calculate total revenue grouped by month
2. THE API SHALL include only orders with status "completed" in revenue calculations
3. THE Admin_Panel SHALL display revenue data in a table format with month and total revenue columns
4. THE API SHALL return revenue data for the last 12 months by default
5. THE API SHALL format revenue amounts as decimal numbers with two decimal places

### Requirement 9: Product Category Management

**User Story:** Как администратор, я хочу управлять категориями товаров, чтобы организовать каталог магазина.

#### Acceptance Criteria

1. WHEN an Administrator creates a category, THE API SHALL store the category name in the Database
2. WHEN an Administrator updates a category, THE API SHALL modify the category name in the Database
3. WHEN an Administrator deletes a category, THE API SHALL remove the category only if no products are assigned to it
4. THE API SHALL ensure category names are unique
5. WHEN a User requests categories, THE API SHALL return all categories with product count for each category

### Requirement 10: RESTful API Design

**User Story:** Как разработчик клиентского приложения, я хочу использовать стандартный RESTful API, чтобы легко интегрировать функциональность.

#### Acceptance Criteria

1. THE API SHALL follow RESTful conventions for resource naming and HTTP methods
2. THE API SHALL return JSON responses for all endpoints
3. THE API SHALL use appropriate HTTP status codes: 200 for success, 201 for creation, 400 for validation errors, 401 for authentication errors, 404 for not found, 500 for server errors
4. THE API SHALL include error messages in JSON format with descriptive text
5. THE API SHALL support CORS headers for cross-origin requests
6. THE API SHALL validate all input data and return validation errors with field-specific messages

### Requirement 11: Database Schema and Migrations

**User Story:** Как разработчик, я хочу иметь структурированную схему базы данных, чтобы обеспечить целостность данных.

#### Acceptance Criteria

1. THE Flower_Shop_System SHALL use MariaDB as the database engine
2. THE Database SHALL include tables for users, products, categories, orders, and order_items
3. THE Database SHALL enforce foreign key constraints between related tables
4. THE Flower_Shop_System SHALL provide Laravel migrations for all database tables
5. THE Database SHALL use appropriate data types for all columns
6. THE Database SHALL include indexes on frequently queried columns

### Requirement 12: User Interface Design

**User Story:** Как пользователь, я хочу иметь удобный и привлекательный интерфейс, чтобы комфортно совершать покупки.

#### Acceptance Criteria

1. THE Client_Application SHALL use responsive design that works on desktop and mobile devices
2. THE Client_Application SHALL display product images in appropriate sizes
3. THE Client_Application SHALL provide clear navigation between pages
4. THE Client_Application SHALL display loading indicators during API requests
5. THE Client_Application SHALL show success and error messages to users after actions
6. THE Admin_Panel SHALL provide a separate interface distinct from the customer-facing site

### Requirement 13: Image Upload and Storage

**User Story:** Как администратор, я хочу загружать изображения товаров, чтобы визуально представить продукцию.

#### Acceptance Criteria

1. WHEN an Administrator uploads a product image, THE API SHALL validate that the file is an image format (JPEG, PNG, GIF, WebP)
2. THE API SHALL validate that image file size does not exceed 5 megabytes
3. WHEN an image is uploaded, THE API SHALL store the image in the public storage directory
4. THE API SHALL generate a unique filename for each uploaded image
5. WHEN a product is deleted, THE API SHALL remove the associated image file from storage
6. THE API SHALL return the image URL in product API responses

### Requirement 14: Session Management

**User Story:** Как пользователь, я хочу, чтобы моя сессия сохранялась, чтобы не терять данные корзины при навигации.

#### Acceptance Criteria

1. THE Flower_Shop_System SHALL use Laravel session management for user sessions
2. THE Flower_Shop_System SHALL store cart data in the user session
3. WHEN a user logs out, THE Flower_Shop_System SHALL clear the user session
4. THE Flower_Shop_System SHALL set session timeout to 120 minutes of inactivity
5. WHEN a session expires, THE Flower_Shop_System SHALL redirect the user to the login page

### Requirement 15: Data Validation and Security

**User Story:** Как владелец системы, я хочу обеспечить безопасность данных, чтобы защитить информацию пользователей и бизнеса.

#### Acceptance Criteria

1. THE API SHALL validate all user input using Laravel validation rules
2. THE API SHALL sanitize user input to prevent SQL injection attacks
3. THE API SHALL protect against Cross-Site Scripting (XSS) attacks by escaping output
4. THE API SHALL use CSRF token protection for all state-changing requests
5. THE API SHALL implement rate limiting to prevent abuse
6. THE Database SHALL store only hashed passwords using bcrypt algorithm
