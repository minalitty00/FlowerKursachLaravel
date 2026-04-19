@extends('layouts.app')

@section('title', 'Регистрация')

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
            <h1>Создать аккаунт</h1>
            <p>Присоединяйтесь к магазину Флора сегодня</p>
        </div>
        
        <form action="{{ route('register.post') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="name" class="form-label">Полное имя</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="form-input @error('name') error @enderror"
                    value="{{ old('name') }}"
                    required
                    autofocus
                >
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">Электронная почта</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-input @error('email') error @enderror"
                    value="{{ old('email') }}"
                    required
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
                <small style="color: #666; font-size: 0.875rem;">Минимум 8 символов</small>
            </div>
            
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Подтвердите пароль</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    class="form-input"
                    required
                >
            </div>
            
            <button type="submit" class="btn btn-primary">
                Зарегистрироваться
            </button>
        </form>
        
        <div class="auth-footer">
            Уже есть аккаунт? <a href="{{ route('login') }}">Войдите здесь</a>
        </div>
    </div>
</div>
@endsection
