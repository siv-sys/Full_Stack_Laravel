@extends('admin.layouts.app')
@section('title', 'Edit Category')

@section('content')
<div class="card" style="max-width: 600px;">
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required>
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            @if($category->image)
                <div style="margin-bottom: 0.5rem;">
                    <img src="{{ asset(Storage::url($category->image)) }}" class="img-thumb" alt="{{ $category->name }}">
                </div>
            @endif
            <input type="file" name="image" id="image" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
