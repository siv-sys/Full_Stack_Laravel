@extends('admin.layouts.app')
@section('title', 'Add Product')

@section('content')
<div class="card" style="max-width: 600px;">
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.products._form')
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>
@endsection
