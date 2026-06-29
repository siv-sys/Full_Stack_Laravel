@extends('admin.layouts.app')
@section('title', 'Add Category')

@section('content')
<a href="{{ route('admin.categories.index') }}" class="back-link">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
    Back to Categories
</a>

<div class="card" style="max-width: 640px;">
    <div class="card-header">
        <h3 class="card-title">New Category</h3>
    </div>
    <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Category Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="e.g. Electronics" required class="{{ $errors->has('name') ? 'is-error' : '' }}">
            @error('name')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="image">Image (file)</label>
            <input type="file" name="image" id="image" accept="image/*">
            <div class="form-hint">Recommended: 400x400px, JPG or PNG</div>
        </div>
        <div class="form-group">
            <label for="image_url">Or paste an Image URL</label>
            <input type="url" name="image_url" id="image_url" value="{{ old('image_url') }}" placeholder="https://example.com/image.jpg" class="{{ $errors->has('image_url') ? 'is-error' : '' }}">
            @error('image_url')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <script>
        (function() {
            var fileInput = document.getElementById('image');
            var urlInput = document.getElementById('image_url');
            fileInput.addEventListener('change', function() {
                if (fileInput.files.length > 0) {
                    urlInput.value = '';
                    urlInput.disabled = true;
                    urlInput.style.opacity = '0.5';
                } else {
                    urlInput.disabled = false;
                    urlInput.style.opacity = '1';
                }
            });
            urlInput.addEventListener('input', function() {
                if (urlInput.value.trim() !== '') {
                    fileInput.value = '';
                    fileInput.disabled = true;
                    fileInput.style.opacity = '0.5';
                } else {
                    fileInput.disabled = false;
                    fileInput.style.opacity = '1';
                }
            });
        })();
        </script>
        <div style="display: flex; gap: 0.75rem; padding-top: 0.5rem;">
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Create Category
            </button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>
@endsection
