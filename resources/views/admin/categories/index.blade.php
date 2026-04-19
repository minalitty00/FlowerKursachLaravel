@extends('layouts.admin')

@section('title', 'Manage Categories')

@section('content')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .category-card {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .category-card h3 {
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
        color: #333;
    }
    
    .category-card p {
        color: #666;
        margin-bottom: 1rem;
    }
    
    .category-actions {
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
    
    .create-form {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #333;
    }
    
    .form-group input {
        width: 100%;
        max-width: 400px;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }
    
    .form-inline {
        display: flex;
        gap: 1rem;
        align-items: end;
    }
    
    @media (max-width: 768px) {
        .categories-grid {
            grid-template-columns: 1fr;
        }
        
        .form-inline {
            flex-direction: column;
            align-items: stretch;
        }
        
        .form-group input {
            max-width: 100%;
        }
    }
</style>

<h2 style="margin-bottom: 2rem;">Manage Categories</h2>

<!-- Create Category Form -->
<div class="create-form">
    <h3 style="margin-bottom: 1rem;">Create New Category</h3>
    <form action="{{ route('admin.categories.store') }}" method="POST" class="form-inline">
        @csrf
        <div class="form-group" style="flex: 1;">
            <label for="name">Category Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        <button type="submit" class="btn-primary">Create Category</button>
    </form>
</div>

<!-- Categories List -->
@if($categories->count() > 0)
    <div class="categories-grid">
        @foreach($categories as $category)
            <div class="category-card">
                <h3>{{ $category->name }}</h3>
                <p>{{ $category->products_count }} product(s)</p>
                
                <div class="category-actions">
                    <button onclick="editCategory({{ $category->id }}, '{{ $category->name }}')" class="btn btn-edit">
                        Edit
                    </button>
                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this category? This will only work if there are no products in this category.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@else
    <p style="text-align: center; color: #666; padding: 2rem;">No categories found. Create your first category!</p>
@endif

<!-- Edit Modal -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 8px; max-width: 500px; width: 90%;">
        <h3 style="margin-bottom: 1rem;">Edit Category</h3>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="edit_name">Category Name</label>
                <input type="text" id="edit_name" name="name" required>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn-primary">Update Category</button>
                <button type="button" onclick="closeEditModal()" class="btn btn-edit" style="background-color: #6c757d;">Cancel</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function editCategory(id, name) {
        document.getElementById('editModal').style.display = 'flex';
        document.getElementById('edit_name').value = name;
        document.getElementById('editForm').action = '/admin/categories/' + id;
    }
    
    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }
    
    // Close modal on outside click
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });
</script>
@endpush
@endsection
