@extends('layouts.app')

@section('title', 'Вход')

@section('content')
<style>
    .auth-container {
        max-width: 450px;
        margin: 3rem auto;
    }
    
    .auth-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 2.5rem;
    }
    
    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .auth-header h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .auth-header p {
        color: #666;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #333;
    }
    
    .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
        transition: border-color 0.3s;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #c94b8c;
    }
    
    .form-input.error {
        border-color: #f44336;
    }
    
    .error-message {
        color: #f44336;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    .form-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }
    
    .form-checkbox input {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    
    .form-checkbox label {
        cursor: pointer;
        user-select: none;
    }
    
    .btn {
        width: 100%;
        padding: 1rem;
        border: none;
        border-radius: 4px;
        font-size: 1rem;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-primary {
        background-color: #c94b8c;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #a83d73;
    }
    
    .auth-footer {
        text-align: center;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #eee;
    }
    
    .auth-footer a {
        color: #c94b8c;
        text-decoration: none;
        font-weight: 500;
    }
    
    .auth-footer a:hover {
        text-decoration: underline;
    }
</style>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>С возвращением</h1>
            <p>Войдите в свой аккаунт</p>
        </div>
        
        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="email" class="form-label">Электронная почта</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-input @error('email') error @enderror"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Пароль</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input @error('password') error @enderror"
                    required
                >
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-checkbox">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Запомнить меня</label>
            </div>
            
            <button type="submit" class="btn btn-primary">
                Войти
            </button>
        </form>
        
        <div class="auth-footer">
            Нет аккаунта? <a href="{{ route('register') }}">Зарегистрируйтесь здесь</a>
        </div>
    </div>
</div>
@endsection
