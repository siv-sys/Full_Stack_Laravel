@extends('admin.layouts.app')
@section('title', 'Products')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <span class="page-subtitle">Manage your product catalog</span>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        Add Product
    </a>
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
                        <div class="img-placeholder">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z"/></svg>
                        </div>
                    @endif
                </td>
                <td style="font-weight: 600;">{{ $product->name }}</td>
                <td>
                    @if($product->category)
                        <span class="badge badge-customer">{{ $product->category->name }}</span>
                    @else
                        <span style="color: var(--gray-400);">—</span>
                    @endif
                </td>
                <td style="font-weight: 600;">${{ number_format($product->price, 2) }}</td>
                <td>
                    @if($product->stock <= 0)
                        <span class="badge badge-cancelled">Out of stock</span>
                    @elseif($product->stock <= 5)
                        <span class="badge badge-pending">{{ $product->stock }} left</span>
                    @else
                        <span style="color: var(--success); font-weight: 600;">{{ $product->stock }}</span>
                    @endif
                </td>
                <td>
                    <div class="btn-group">
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-ghost btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this product?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm" style="color: var(--danger); background: transparent; border: 1px solid var(--gray-300);">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="table-empty">
                    <div class="table-empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/></svg>
                    </div>
                    <div class="table-empty-text">No products yet</div>
                    <div class="table-empty-sub">Add your first product to get started.</div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($products->hasPages())
    <div class="pagination">
        @if($products->onFirstPage())
            <span class="disabled">&laquo;</span>
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
            <span class="disabled">&raquo;</span>
        @endif
    </div>
    @endif
</div>
@endsection
