<div class="form-group">
    <label for="name">Name</label>
    <input type="text" name="name" id="name" value="{{ old('name', $user->name ?? '') }}" required style="{{ $errors->has('name') ? 'border-color:#dc2626;' : '' }}">
    @error('name')
        <span style="color:#dc2626;font-size:0.8rem;">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="email">Email</label>
    <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" required style="{{ $errors->has('email') ? 'border-color:#dc2626;' : '' }}">
    @error('email')
        <span style="color:#dc2626;font-size:0.8rem;">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="password">Password{{ isset($user) ? ' (leave blank to keep current)' : '' }}</label>
    <input type="password" name="password" id="password" {{ isset($user) ? '' : 'required' }} style="{{ $errors->has('password') ? 'border-color:#dc2626;' : '' }}">
    @if(isset($user))
        <span style="color:#6b7280;font-size:0.8rem;">Only fill in if you want to change the password.</span>
    @endif
    @error('password')
        <span style="color:#dc2626;font-size:0.8rem;">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="password_confirmation">Confirm Password</label>
    <input type="password" name="password_confirmation" id="password_confirmation" {{ isset($user) ? '' : 'required' }}>
</div>

<div class="form-group">
    <label for="role">Role</label>
    <select name="role" id="role" required style="{{ $errors->has('role') ? 'border-color:#dc2626;' : '' }}">
        <option value="customer" {{ old('role', isset($user) && $user->is_admin ? 'admin' : 'customer') === 'customer' ? 'selected' : '' }}>Customer</option>
        <option value="admin" {{ old('role', isset($user) && $user->is_admin ? 'admin' : 'customer') === 'admin' ? 'selected' : '' }}>Admin</option>
    </select>
    @error('role')
        <span style="color:#dc2626;font-size:0.8rem;">{{ $message }}</span>
    @enderror
</div>
