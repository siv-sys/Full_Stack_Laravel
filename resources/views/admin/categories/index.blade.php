@extends('admin.layouts.app')
@section('title', 'Categories')

@section('content')
<div style="margin-bottom: 1rem;">
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Add Category</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Products</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td>
                    @if($category->image)
                        <img src="{{ asset(Storage::url($category->image)) }}" class="img-thumb" alt="{{ $category->name }}">
                    @else
                        <span style="color:#9ca3af;">No image</span>
                    @endif
                </td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->slug }}</td>
                <td>{{ $category->products_count }}</td>
                <td>
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary btn-sm">Edit</a>
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" style="display:inline;" onsubmit="return confirm('Delete this category?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:#6b7280;">No categories yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
