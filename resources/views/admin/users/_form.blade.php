<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 1.25rem;">
    <div class="form-group" style="grid-column: 1 / -1;">
        <label for="name">Full Name</label>
        <input type="text" name="name" id="name" value="{{ old('name', $user->name ?? '') }}" placeholder="John Doe" required class="{{ $errors->has('name') ? 'is-error' : '' }}">
        @error('name')
            <div class="form-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group" style="grid-column: 1 / -1;">
        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" placeholder="john@example.com" required class="{{ $errors->has('email') ? 'is-error' : '' }}">
        @error('email')
            <div class="form-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="password">Password{{ isset($user) ? '' : '' }}</label>
        <input type="password" name="password" id="password" placeholder="{{ isset($user) ? 'Leave blank to keep current' : 'Enter password' }}" {{ isset($user) ? '' : 'required' }} class="{{ $errors->has('password') ? 'is-error' : '' }}">
        @if(isset($user))
            <div class="form-hint">Only fill in if you want to change the password.</div>
        @endif
        @error('password')
            <div class="form-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repeat password" {{ isset($user) ? '' : 'required' }}>
    </div>
</div>

<div class="form-group">
    <label for="role">Role</label>
    <select name="role" id="role" required class="{{ $errors->has('role') ? 'is-error' : '' }}">
        <option value="customer" {{ old('role', isset($user) && $user->is_admin ? 'admin' : 'customer') === 'customer' ? 'selected' : '' }}>Customer</option>
        <option value="admin" {{ old('role', isset($user) && $user->is_admin ? 'admin' : 'customer') === 'admin' ? 'selected' : '' }}>Admin</option>
    </select>
    @error('role')
        <div class="form-error">{{ $message }}</div>
    @enderror
</div>
