@extends('admin.layouts.app')
@section('title', 'Add Product')

@section('content')
<a href="{{ route('admin.products.index') }}" class="back-link">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
    Back to Products
</a>

<div class="card" style="max-width: 640px;">
    <div class="card-header">
        <h3 class="card-title">New Product</h3>
    </div>
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.products._form')
        <div style="display: flex; gap: 0.75rem; padding-top: 0.5rem;">
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Create Product
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>
@endsection
