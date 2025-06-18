@extends('layouts.main')

@section('title', 'Edit Profile')

@section('content')
<!-- Main Content -->
<main class="main-content">
    <!-- Page Header -->
    <div class="page-header animate">
        <h1><i class="fas fa-user-edit"></i> Edit Profile</h1>
        <p>Kelola informasi akun dan keamanan Anda</p>
    </div>

    {{-- Alert untuk error --}}
    @if ($errors->any())
        <div class="alert alert-danger animate">
            <i class="fas fa-exclamation-circle"></i>
            <div class="alert-content">
                <strong>Oops!</strong> Ada masalah dengan input Anda:
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="alert-close">&times;</button>
        </div>
    @endif

    {{-- Alert untuk success --}}
    @if (session('success'))
        <div class="alert alert-success animate">
            <i class="fas fa-check-circle"></i>
            <div class="alert-content">
                {{ session('success') }}
            </div>
            <button type="button" class="alert-close">&times;</button>
        </div>
    @endif

    <!-- Profile Information Form -->
    <div class="form-card animate">
        <form action="{{ route('profile.update') }}" method="POST" id="profileForm">
            @csrf
            @method('PUT')
            
            <!-- Personal Information Section -->
            <div class="form-section">
                <h6 class="form-section-title">
                    <i class="fas fa-user"></i>
                    Informasi Personal
                </h6>
                
                <div class="form-grid">
                    <!-- Nama Lengkap -->
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Lengkap *</label>
                        <input type="text" 
                               class="form-input @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               required>
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" 
                               class="form-input @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               required>
                        @error('email')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="form-group">
                        <label for="phone" class="form-label">Nomor Telepon *</label>
                        <input type="text" 
                               class="form-input @error('phone') is-invalid @enderror" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $user->phone) }}" 
                               placeholder="08123456789"
                               required>
                        @error('phone')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nomor KTP -->
                    <div class="form-group">
                        <label for="nomor_ktp" class="form-label">No. KTP *</label>
                        <input type="text" 
                               class="form-input @error('nomor_ktp') is-invalid @enderror" 
                               id="nomor_ktp" 
                               name="nomor_ktp" 
                               value="{{ old('nomor_ktp', $user->nomor_ktp) }}" 
                               placeholder="1234567891234567"
                               maxlength="16"
                               required>
                        @error('nomor_ktp')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div class="form-group">
                        <label for="birth_date" class="form-label">Tanggal Lahir *</label>
                        <input type="date" 
                               class="form-input @error('birth_date') is-invalid @enderror" 
                               id="birth_date" 
                               name="birth_date" 
                               value="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}"
                               required>
                        @error('birth_date')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="form-group full-width">
                        <label for="address" class="form-label">Alamat *</label>
                        <textarea class="form-textarea @error('address') is-invalid @enderror" 
                                  id="address" 
                                  name="address" 
                                  rows="3" 
                                  placeholder="Masukkan alamat lengkap"
                                  required>{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg" id="profileBtn">
                    <i class="fas fa-save"></i>
                    Simpan Perubahan
                </button>
                <a href="/dashboard" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </form>
    </div>

    <!-- Change Password Form -->
    <div class="form-card animate">
        <form action="{{ route('password.update') }}" method="POST" id="passwordForm">
            @csrf
            @method('PUT')
            
            <!-- Password Section -->
            <div class="form-section">
                <h6 class="form-section-title">
                    <i class="fas fa-lock"></i>
                    Ubah Password
                </h6>
                
                <div class="form-grid password-grid">
                    <!-- Current Password -->
                    <div class="form-group">
                        <label for="current_password" class="form-label">Password Saat Ini *</label>
                        <div class="password-input-group">
                            <input type="password" 
                                   class="form-input @error('current_password') is-invalid @enderror" 
                                   id="current_password" 
                                   name="current_password" 
                                   required>
                            <button type="button" class="password-toggle" onclick="togglePassword('current_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">Password Baru *</label>
                        <div class="password-input-group">
                            <input type="password" 
                                   class="form-input @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   minlength="8"
                                   required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <small class="form-help">Minimal 8 karakter</small>
                        @error('password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru *</label>
                        <div class="password-input-group">
                            <input type="password" 
                                   class="form-input" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   minlength="8"
                                   required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <small class="form-help">Ulangi password baru</small>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-actions">
                <button type="submit" class="btn btn-danger btn-lg" id="passwordBtn">
                    <i class="fas fa-key"></i>
                    Ubah Password
                </button>
            </div>
        </form>
    </div>
</main>

<!-- Styles -->
<style>
.page-header {
    background: white;
    padding: 25px;
    border-radius: 15px;
    margin-bottom: 30px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.page-header h1 {
    font-size: 1.8rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 10px;
}

.page-header p {
    color: #7f8c8d;
    margin: 0;
}

.alert {
    background: white;
    border: none;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    display: flex;
    align-items: flex-start;
    gap: 15px;
    position: relative;
}

.alert-success {
    border-left: 5px solid #27ae60;
    color: #2e7d32;
}

.alert-danger {
    border-left: 5px solid #e74c3c;
    color: #d32f2f;
}

.alert-content {
    flex: 1;
}

.alert-close {
    position: absolute;
    top: 15px;
    right: 15px;
    background: none;
    border: none;
    font-size: 18px;
    cursor: pointer;
    color: #7f8c8d;
}

.form-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}

.form-section {
    margin-bottom: 30px;
}

.form-section:last-of-type {
    margin-bottom: 20px;
}

.form-section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #ecf0f1;
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-section-title i {
    color: #3498db;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.password-grid {
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-label {
    font-weight: 500;
    color: #2c3e50;
    margin-bottom: 8px;
    font-size: 14px;
}

.form-input,
.form-select,
.form-textarea {
    padding: 12px 15px;
    border: 2px solid #ecf0f1;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s ease;
    background: white;
    font-family: inherit;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

.form-textarea {
    resize: vertical;
    min-height: 80px;
}

.password-input-group {
    position: relative;
}

.password-input-group .form-input {
    padding-right: 45px;
}

.password-toggle {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #7f8c8d;
    cursor: pointer;
    padding: 5px;
    border-radius: 4px;
    transition: color 0.3s ease;
}

.password-toggle:hover {
    color: #3498db;
}

.form-help {
    color: #7f8c8d;
    font-size: 12px;
    margin-top: 5px;
    font-style: italic;
}

.form-error {
    color: #e74c3c;
    font-size: 12px;
    margin-top: 5px;
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #ecf0f1;
}

.btn-lg {
    padding: 15px 30px;
    font-size: 16px;
}

.btn-danger {
    background: linear-gradient(45deg, #e74c3c, #c0392b);
    color: white;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
}

@media (max-width: 768px) {
    .form-grid,
    .password-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .form-card {
        padding: 20px;
    }
}

/* Loading state */
.btn-loading {
    opacity: 0.6;
    pointer-events: none;
}

.btn-loading i {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>

<!-- JavaScript -->
<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const toggle = input.parentElement.querySelector('.password-toggle i');
    
    if (input.type === 'password') {
        input.type = 'text';
        toggle.classList.remove('fa-eye');
        toggle.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        toggle.classList.remove('fa-eye-slash');
        toggle.classList.add('fa-eye');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const profileForm = document.getElementById('profileForm');
    const passwordForm = document.getElementById('passwordForm');
    const profileBtn = document.getElementById('profileBtn');
    const passwordBtn = document.getElementById('passwordBtn');

    // Prevent double submission for profile form
    if (profileForm) {
        profileForm.addEventListener('submit', function() {
            profileBtn.disabled = true;
            profileBtn.classList.add('btn-loading');
            profileBtn.innerHTML = '<i class="fas fa-spinner"></i> Menyimpan...';
        });
    }

    // Prevent double submission for password form
    if (passwordForm) {
        passwordForm.addEventListener('submit', function() {
            passwordBtn.disabled = true;
            passwordBtn.classList.add('btn-loading');
            passwordBtn.innerHTML = '<i class="fas fa-spinner"></i> Mengubah...';
        });
    }

    // Close alert functionality
    document.querySelectorAll('.alert-close').forEach(button => {
        button.addEventListener('click', function() {
            this.parentElement.style.display = 'none';
        });
    });

    // KTP number validation
    const ktpInput = document.getElementById('nomor_ktp');
    if (ktpInput) {
        ktpInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });
    }

    // Phone number validation
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });
    }

    // Password confirmation validation
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            if (passwordInput.value !== this.value) {
                this.setCustomValidity('Password tidak cocok');
            } else {
                this.setCustomValidity('');
            }
        });
    }
});
</script>
@endsection