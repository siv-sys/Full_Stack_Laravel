@extends('admin.layouts.app')
@section('title', 'Edit Product')

@section('content')
<div class="card" style="max-width: 600px;">
    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.products._form')
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
