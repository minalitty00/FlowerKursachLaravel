<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Флора') }} - Админ - @yield('title', 'Панель управления')</title>

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
        
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #ffffff;
            color: #333;
            padding: 2rem 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 8px rgba(0,0,0,0.1);
        }
        
        .sidebar-header {
            padding: 0 1.5rem;
            margin-bottom: 2rem;
        }
        
        .sidebar-header h2 {
            font-size: 1.5rem;
            color: #c94b8c;
        }
        
        .sidebar-header p {
            font-size: 0.875rem;
            color: #666;
            margin-top: 0.25rem;
        }
        
        .sidebar-menu {
            list-style: none;
        }
        
        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: #333;
            text-decoration: none;
            transition: background-color 0.3s, padding-left 0.3s;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: #f8f9fa;
            padding-left: 2rem;
        }
        
        .sidebar-menu a.active {
            border-left: 4px solid #c94b8c;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 2rem;
        }
        
        .top-bar {
            background-color: #c94b8c;
            color: white;
            padding: 1rem 2rem;
            margin: -2rem -2rem 2rem -2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .top-bar h1 {
            font-size: 1.5rem;
            color: white;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-menu a,
        .user-menu button {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        .user-menu a:hover,
        .user-menu button:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .user-menu button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            font-family: inherit;
        }
        
        .btn-primary {
            background-color: #c94b8c;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn-primary:hover {
            background-color: #a83d73;
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
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }
            
            .main-content {
                margin-left: 200px;
                padding: 1rem;
            }
            
            .top-bar {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .sidebar {
                position: static;
                width: 100%;
                height: auto;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>🌸 Админ-панель</h2>
                <p>Управление магазином Флора</p>
            </div>
            
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        📊 Панель управления
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        🌺 Товары
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        📁 Категории
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        📦 Заказы
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.revenue') }}" class="{{ request()->routeIs('admin.revenue') ? 'active' : '' }}">
                        💰 Отчеты о выручке
                    </a>
                </li>
                <li style="margin-top: 2rem; border-top: 1px solid #34495e; padding-top: 1rem;">
                    <a href="{{ route('home') }}">
                        🏠 Вернуться в магазин
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-bar">
                <h1>@yield('title', 'Панель управления')</h1>
                
                <div class="user-menu">
                    <span>{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit">Выход</button>
                    </form>
                </div>
            </div>

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
        </main>
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
    </script>
</body>
</html>
