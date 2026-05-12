@extends('layouts.admin')

@section('title', 'Управление товарами')

@section('content')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    
    .products-table-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .products-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .products-table th,
    .products-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    
    .products-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #333;
    }
    
    .products-table tr:hover {
        background-color: #f8f9fa;
    }
    
    .product-image-thumb {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
    }
    
    .actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        font-size: 0.875rem;
        transition: opacity 0.3s;
    }
    
    .btn:hover {
        opacity: 0.8;
    }
    
    .btn-edit {
        background-color: #0d6efd;
        color: white;
    }
    
    .btn-delete {
        background-color: #dc3545;
        color: white;
    }
    
    @media (max-width: 768px) {
        .products-table {
            font-size: 0.875rem;
        }
        
        .products-table th,
        .products-table td {
            padding: 0.5rem;
        }
        
        .product-image-thumb {
            width: 40px;
            height: 40px;
        }
    }
</style>

<div class="page-header">
    <h2>Управление товарами</h2>
    <a href="{{ route('admin.products.create') }}" class="btn-primary">+ Добавить новый товар</a>
</div>

<div class="products-table-container">
    @if($products->count() > 0)
        <table class="products-table">
            <thead>
                <tr>
                    <th>Изображение</th>
                    <th>Название</th>
                    <th>Категория</th>
                    <th>Цена</th>
                    <th>Остаток</th>
                    <th>Создан</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image-thumb">
                            @else
                                <div class="product-image-thumb" style="background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999;">
                                    —
                                </div>
                            @endif
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ format_price($product->price) }}</td>
                        <td>{{ $product->stock_quantity }}</td>
                        <td>{{ $product->created_at->format('d.m.Y') }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-edit">Редактировать</a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Вы уверены, что хотите удалить этот товар?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete">Удалить</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="padding: 2rem; text-align: center; color: #666;">Товары не найдены. Создайте свой первый товар!</p>
    @endif
</div>
@endsection
