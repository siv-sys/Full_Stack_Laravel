@extends('admin.layouts.app')
@section('title', 'Products')

@section('content')
<div style="margin-bottom: 1rem;">
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add Product</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>
                    @if($product->image)
                        <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset(Storage::url($product->image)) }}" class="img-thumb" alt="{{ $product->name }}">
                    @else
                        <span style="color:#9ca3af;">No image</span>
                    @endif
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name ?? '—' }}</td>
                <td>${{ number_format($product->price, 2) }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary btn-sm">Edit</a>
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this product?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:#6b7280;padding:2rem;">No products yet.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($products->hasPages())
    <div class="pagination">
        @if($products->onFirstPage())
            <span>&laquo;</span>
        @else
            <a href="{{ $products->previousPageUrl() }}">&laquo;</a>
        @endif

        @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
            @if($page == $products->currentPage())
                <span class="current">{{ $page }}</span>
            @else
                <a href="{{ $url }}">{{ $page }}</a>
            @endif
        @endforeach

        @if($products->hasMorePages())
            <a href="{{ $products->nextPageUrl() }}">&raquo;</a>
        @else
            <span>&raquo;</span>
        @endif
    </div>
    @endif
</div>
@endsection
