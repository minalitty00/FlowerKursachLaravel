@extends('layouts.admin')

@section('title', 'Управление пользователями')

@section('content')
<style>
    .page-header {
        margin-bottom: 2rem;
    }
    
    .page-header h1 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    
    .alert {
        padding: 1rem;
        border-radius: 4px;
        margin-bottom: 1.5rem;
    }
    
    .alert-success {
        background-color: #d1e7dd;
        color: #0f5132;
        border: 1px solid #badbcc;
    }
    
    .alert-error {
        background-color: #f8d7da;
        color: #842029;
        border: 1px solid #f5c2c7;
    }
    
    .users-table {
        width: 100%;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-collapse: collapse;
        overflow: hidden;
    }
    
    .users-table th,
    .users-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    
    .users-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #333;
    }
    
    .users-table tr:hover {
        background-color: #f8f9fa;
    }
    
    .users-table tr:last-child td {
        border-bottom: none;
    }
    
    .role-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .role-admin {
        background-color: #c94b8c;
        color: white;
    }
    
    .role-user {
        background-color: #e9ecef;
        color: #495057;
    }
    
    .role-form {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    
    .role-form select {
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 0.875rem;
        background: white;
    }
    
    .role-form button {
        padding: 0.5rem 1rem;
        background-color: #c94b8c;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 0.875rem;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    
    .role-form button:hover {
        background-color: #a83d73;
    }
    
    .user-email {
        color: #666;
        font-size: 0.875rem;
    }
    
    .user-date {
        color: #999;
        font-size: 0.875rem;
    }
    
    @media (max-width: 768px) {
        .users-table {
            font-size: 0.875rem;
        }
        
        .users-table th,
        .users-table td {
            padding: 0.75rem 0.5rem;
        }
        
        .role-form {
            flex-direction: column;
        }
        
        .role-form select,
        .role-form button {
            width: 100%;
        }
    }
</style>

<div class="page-header">
    <h1>Управление пользователями</h1>
</div>

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

<table class="users-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Email</th>
            <th>Роль</th>
            <th>Дата регистрации</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td class="user-email">{{ $user->email }}</td>
                <td>
                    <span class="role-badge {{ $user->role === 'admin' ? 'role-admin' : 'role-user' }}">
                        {{ $user->role === 'admin' ? 'Админ' : 'Пользователь' }}
                    </span>
                </td>
                <td class="user-date">{{ $user->created_at->format('d.m.Y H:i') }}</td>
                <td>
                    @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.updateRole', $user->id) }}" class="role-form">
                            @csrf
                            @method('PUT')
                            <select name="role">
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Пользователь</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Админ</option>
                            </select>
                            <button type="submit">Изменить</button>
                        </form>
                    @else
                        <span style="color: #999; font-size: 0.875rem;">Это вы</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@if($users->isEmpty())
    <p style="text-align: center; color: #666; padding: 2rem;">Пользователей пока нет</p>
@endif
@endsection