<div class="form-group">
    <label for="category_id">Category</label>
    <select name="category_id" id="category_id" required style="{{ $errors->has('category_id') ? 'border-color:#dc2626;' : '' }}">
        <option value="">Select category</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('category_id')
        <span style="color:#dc2626;font-size:0.8rem;">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="name">Name</label>
    <input type="text" name="name" id="name" value="{{ old('name', $product->name ?? '') }}" required style="{{ $errors->has('name') ? 'border-color:#dc2626;' : '' }}">
    @error('name')
        <span style="color:#dc2626;font-size:0.8rem;">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="description">Description</label>
    <textarea name="description" id="description" style="{{ $errors->has('description') ? 'border-color:#dc2626;' : '' }}">{{ old('description', $product->description ?? '') }}</textarea>
    @error('description')
        <span style="color:#dc2626;font-size:0.8rem;">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="price">Price</label>
    <input type="number" name="price" id="price" step="0.01" min="0" value="{{ old('price', $product->price ?? '') }}" required style="{{ $errors->has('price') ? 'border-color:#dc2626;' : '' }}">
    @error('price')
        <span style="color:#dc2626;font-size:0.8rem;">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="stock">Stock</label>
    <input type="number" name="stock" id="stock" min="0" value="{{ old('stock', $product->stock ?? 0) }}" required style="{{ $errors->has('stock') ? 'border-color:#dc2626;' : '' }}">
    @error('stock')
        <span style="color:#dc2626;font-size:0.8rem;">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="image">Image (file)</label>
    @if(isset($product) && $product->image)
        <div style="margin-bottom:0.5rem;">
            <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset(Storage::url($product->image)) }}" class="img-thumb" alt="{{ $product->name }}">
        </div>
    @endif
    <input type="file" name="image" id="image" accept="image/*">
    @error('image')
        <span style="color:#dc2626;font-size:0.8rem;">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="image_url">Or Image URL</label>
    <input type="url" name="image_url" id="image_url" value="{{ old('image_url') }}" placeholder="https://example.com/image.jpg" style="{{ $errors->has('image_url') ? 'border-color:#dc2626;' : '' }}">
    @error('image_url')
        <span style="color:#dc2626;font-size:0.8rem;">{{ $message }}</span>
    @enderror
</div>
