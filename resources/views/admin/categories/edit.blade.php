@extends('admin.layouts.app')
@section('title', 'Edit Category')

@section('content')
<a href="{{ route('admin.categories.index') }}" class="back-link">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
    Back to Categories
</a>

<div class="card" style="max-width: 640px;">
    <div class="card-header">
        <h3 class="card-title">Edit: {{ $category->name }}</h3>
    </div>
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Category Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required class="{{ $errors->has('name') ? 'is-error' : '' }}">
            @error('name')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            @if($category->image)
                <input type="hidden" name="remove_image" id="remove_image" value="0">
                <div id="image-preview" style="display: flex; align-items: flex-start; gap: 0.75rem; margin-bottom: 0.75rem; padding: 0.75rem; background: var(--gray-50); border-radius: var(--radius); border: 1px solid var(--gray-200);">
                    <img src="{{ str_starts_with($category->image, 'http') ? $category->image : asset(Storage::url($category->image)) }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: var(--radius); border: 1px solid var(--gray-200);" alt="{{ $category->name }}">
                    <div style="display: flex; flex-direction: column; gap: 0.25rem; justify-content: center;">
                        <span style="font-size: 0.8rem; color: var(--gray-600); font-weight: 500;">Current image</span>
                        <button type="button" onclick="document.getElementById('remove_image').value='1';document.getElementById('image-preview').style.display='none';document.getElementById('image-removed-notice').style.display='flex';" class="btn btn-sm" style="color: var(--danger); background: #fff; border: 1px solid var(--gray-300); font-size: 0.75rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                            Remove Image
                        </button>
                    </div>
                </div>
                <div id="image-removed-notice" style="display: none; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; padding: 0.75rem; background: #fef2f2; border-radius: var(--radius); border: 1px solid #fecaca;">
                    <span style="font-size: 0.8rem; color: #991b1b; font-weight: 500;">Image marked for removal.</span>
                    <button type="button" onclick="document.getElementById('remove_image').value='0';document.getElementById('image-preview').style.display='flex';document.getElementById('image-removed-notice').style.display='none';" class="btn btn-sm" style="background: #fff; border: 1px solid var(--gray-300); font-size: 0.75rem; color: var(--gray-700);">Undo</button>
                </div>
            @endif
            <input type="file" name="image" id="image" accept="image/*">
            <div class="form-hint">{{ $category->image ? 'Upload a new file to replace, or remove the current one above' : 'Recommended: 400x400px, JPG or PNG' }}</div>
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
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>
@endsection
