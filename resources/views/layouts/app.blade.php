<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Флора') }} - @yield('title', 'Главная')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Figtree', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #60d18a;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Navigation */
        nav {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        nav .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 20px;
        }
        
        nav .logo {
            font-size: 1.5rem;
            font-weight: 600;
            color: #c94b8c;
            text-decoration: none;
        }
        
        nav ul {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }
        
        nav a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        nav a:hover {
            color: #c94b8c;
        }
        
        nav .cart-badge {
            background-color: #c94b8c;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.75rem;
            margin-left: 4px;
        }
        
        /* Main content */
        main {
            min-height: calc(100vh - 200px);
            padding: 2rem 0;
        }
        
        /* Footer */
        footer {
            background-color: #c94b8c;
            color: #fff;
            text-align: center;
            padding: 2rem 0;
            margin-top: 4rem;
        }
        
        /* Alerts */
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            animation: slideDown 0.3s ease-out;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Loading indicator */
        .loading {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(255, 255, 255, 0.9);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 9999;
        }
        
        .loading.active {
            display: block;
        }
        
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #c94b8c;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            nav ul {
                gap: 1rem;
                font-size: 0.9rem;
            }
            
            nav .logo {
                font-size: 1.2rem;
            }
            
            .container {
                padding: 0 15px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="container">
            <a href="{{ route('home') }}" class="logo">🌸 Флора</a>
            
            <ul>
                <li><a href="{{ route('home') }}">Главная</a></li>
                <li><a href="{{ route('products.index') }}">Товары</a></li>
                
                @auth
                    @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('admin.dashboard') }}" style="color: #c94b8c; font-weight: 600;">Админ-панель</a></li>
                    @endif
                    <li><a href="{{ route('orders.index') }}">Мои заказы</a></li>
                    <li>
                        <a href="{{ route('cart.index') }}">
                            Корзина
                            @if(session('cart') && count(session('cart')) > 0)
                                <span class="cart-badge">{{ count(session('cart')) }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" style="background: none; border: none; color: #333; font-weight: 500; cursor: pointer; font-size: 1rem;">
                                Выход
                            </button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}">Вход</a></li>
                    <li><a href="{{ route('register') }}">Регистрация</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <div class="container">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info">
                    {{ session('info') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <ul style="list-style: none; padding: 0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} Флора. Все права защищены.</p>
        </div>
    </footer>

    <!-- Loading Indicator -->
    <div class="loading" id="loadingIndicator">
        <div class="spinner"></div>
        <p style="text-align: center; margin-top: 1rem;">Загрузка...</p>
    </div>

    @stack('scripts')
    
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
        });
        
        // Show loading indicator for AJAX requests
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form[data-ajax="true"]');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    document.getElementById('loadingIndicator').classList.add('active');
                });
            });
        });
    </script>
</body>
</html>
