@extends('layouts.main')

@section('title', 'Buat Antrian')

@section('content')
<!-- Main Content -->
<main class="main-content">
    <!-- Page Header -->
    <div class="page-header animate">
        <h1><i class="fas fa-plus-circle"></i> Buat Antrian</h1>
        <p>Isi form berikut untuk membuat antrian baru</p>
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

    <!-- Form Card -->
    <div class="form-card animate">
        <form action="{{ route('antrian.store') }}" method="POST" id="antrianForm">
            @csrf
            
            <!-- Personal Information Section -->
            <div class="form-section">
                <h6 class="form-section-title">
                    <i class="fas fa-user"></i>
                    Informasi Personal
                </h6>
                
                <div class="form-grid">
                    <!-- Nama Lengkap - READONLY -->
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" 
                               class="form-input readonly-input" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', Auth::user()->name) }}" 
                               readonly 
                               tabindex="-1">
                    </div>

                    <!-- Nomor HP - READONLY -->
                    <div class="form-group">
                        <label for="phone" class="form-label">Nomor HP</label>
                        <input type="text" 
                               class="form-input readonly-input" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', Auth::user()->phone) }}" 
                               readonly 
                               tabindex="-1">
                    </div>

                    <!-- Jenis Kelamin - READONLY -->
                    <div class="form-group">
                        <label for="gender" class="form-label">Jenis Kelamin</label>
                        <input type="text" 
                               class="form-input readonly-input" 
                               id="gender" 
                               name="gender" 
                               value="{{ old('gender', Auth::user()->gender) }}" 
                               readonly 
                               tabindex="-1">
                    </div>
                </div>
            </div>

            <!-- Medical Information Section -->
            <div class="form-section">
                <h6 class="form-section-title">
                    <i class="fas fa-stethoscope"></i>
                    Informasi Medis
                </h6>
                
                <div class="form-grid">
                    <!-- Poli - CUSTOM DROPDOWN -->
                    <div class="form-group">
                        <label for="poli" class="form-label">Poli</label>
                        <div class="custom-dropdown" data-name="poli">
                            <div class="dropdown-trigger @error('poli') is-invalid @enderror" id="poli-trigger">
                                <span class="dropdown-text">-- Pilih Poli --</span>
                                <i class="fas fa-chevron-down dropdown-icon"></i>
                            </div>
                            <div class="dropdown-menu" id="poli-menu">
                                <div class="dropdown-search">
                                    <input type="text" placeholder="Cari poli..." class="search-input">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                                <div class="dropdown-options">
                                    @foreach($poli as $p)
                                        <div class="dropdown-option" data-value="{{ $p->nama }}">
                                            <span>{{ $p->nama }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <input type="hidden" name="poli" id="poli" value="{{ old('poli') }}" required>
                        </div>
                        @error('poli')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Dokter - CUSTOM DROPDOWN -->
                    <div class="form-group">
                        <label for="doctor_id" class="form-label">Dokter</label>
                        <div class="custom-dropdown" data-name="doctor_id">
                            <div class="dropdown-trigger @error('doctor_id') is-invalid @enderror" id="doctor-trigger">
                                <span class="dropdown-text">-- Pilih Dokter --</span>
                                <i class="fas fa-chevron-down dropdown-icon"></i>
                            </div>
                            <div class="dropdown-menu" id="doctor-menu">
                                <div class="dropdown-search">
                                    <input type="text" placeholder="Cari dokter..." class="search-input">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                                <div class="dropdown-options">
                                    @foreach($doctors as $doctor)
                                        <div class="dropdown-option" data-value="{{ $doctor->doctor_id }}" data-text="{{ $doctor->nama }} - ({{ $doctor->mulai_praktek }} - {{ $doctor->selesai_praktek }})">
                                            <div class="doctor-info">
                                                <span class="doctor-name">{{ $doctor->nama }}</span>
                                                <span class="doctor-time">{{ $doctor->mulai_praktek }} - {{ $doctor->selesai_praktek }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <input type="hidden" name="doctor_id" id="doctor_id" value="{{ old('doctor_id') }}" required>
                        </div>
                        @error('doctor_id')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Antrian - EDITABLE -->
                    <div class="form-group">
                        <label for="tanggal" class="form-label">Tanggal Antrian</label>
                        <input type="date" 
                               class="form-input @error('tanggal') is-invalid @enderror" 
                               id="tanggal" 
                               name="tanggal" 
                               value="{{ old('tanggal') }}" 
                               min="{{ date('Y-m-d') }}"
                               required>
                        @error('tanggal')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                    <i class="fas fa-plus-circle"></i>
                    Buat Antrian
                </button>
                <a href="/antrian" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </form>
    </div>
</main>

<!-- Form Styles -->
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

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    font-weight: 500;
    color: #2c3e50;
    margin-bottom: 8px;
    font-size: 14px;
}

.form-input,
.form-select {
    padding: 12px 15px;
    border: 2px solid #ecf0f1;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s ease;
    background: white;
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

.readonly-input {
    background-color: #f8f9fa !important;
    color: #6c757d !important;
    border-color: #dee2e6 !important;
    cursor: not-allowed !important;
    opacity: 0.8;
    pointer-events: none;
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

/* Custom Dropdown Styles */
.custom-dropdown {
    position: relative;
    width: 100%;
}

.dropdown-trigger {
    width: 100%;
    padding: 12px 45px 12px 15px;
    border: 2px solid #ecf0f1;
    border-radius: 8px;
    background: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.3s ease;
    user-select: none;
    min-height: 48px;
}

.dropdown-trigger:hover {
    border-color: #bdc3c7;
}

.dropdown-trigger.active {
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

.dropdown-trigger.is-invalid {
    border-color: #e74c3c;
}

.dropdown-text {
    flex: 1;
    color: #2c3e50;
    font-size: 14px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.dropdown-text.placeholder {
    color: #95a5a6;
}

.dropdown-icon {
    color: #95a5a6;
    transition: transform 0.3s ease;
    font-size: 12px;
}

.dropdown-trigger.active .dropdown-icon {
    transform: rotate(180deg);
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 2px solid #ecf0f1;
    border-top: none;
    border-radius: 0 0 8px 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    z-index: 1000;
    max-height: 300px;
    overflow: hidden;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
}

.dropdown-menu.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-search {
    position: relative;
    padding: 10px;
    border-bottom: 1px solid #ecf0f1;
    background: #f8f9fa;
}

.search-input {
    width: 100%;
    padding: 8px 35px 8px 12px;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    font-size: 14px;
    outline: none;
}

.search-input:focus {
    border-color: #3498db;
}

.search-icon {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: #95a5a6;
    font-size: 12px;
}

.dropdown-options {
    max-height: 200px;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
}

.dropdown-option {
    padding: 12px 15px;
    cursor: pointer;
    border-bottom: 1px solid #f8f9fa;
    transition: background-color 0.2s ease;
    display: flex;
    align-items: center;
}

.dropdown-option:hover {
    background-color: #f8f9fa;
}

.dropdown-option.selected {
    background-color: #3498db;
    color: white;
}

.dropdown-option.hidden {
    display: none;
}

.doctor-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.doctor-name {
    font-weight: 500;
    font-size: 14px;
}

.doctor-time {
    font-size: 12px;
    color: #7f8c8d;
}

.dropdown-option.selected .doctor-time {
    color: rgba(255, 255, 255, 0.8);
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .form-card {
        padding: 20px;
    }

    .dropdown-menu {
        max-height: 250px;
    }

    .dropdown-options {
        max-height: 160px;
    }

    .dropdown-trigger {
        min-height: 52px;
        padding: 15px 45px 15px 15px;
    }

    .dropdown-option {
        padding: 15px;
        min-height: 60px;
    }

    .doctor-name {
        font-size: 15px;
    }

    .doctor-time {
        font-size: 13px;
    }
}

/* Touch-friendly adjustments */
@media (max-width: 480px) {
    .dropdown-trigger {
        min-height: 56px;
        padding: 18px 50px 18px 18px;
    }

    .dropdown-option {
        padding: 18px;
        min-height: 70px;
    }

    .search-input {
        padding: 12px 40px 12px 15px;
        font-size: 16px; /* Prevents zoom on iOS */
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

/* Backdrop for mobile */
.dropdown-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.3);
    z-index: 999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.dropdown-backdrop.show {
    opacity: 1;
    visibility: visible;
}

@media (max-width: 768px) {
    .dropdown-menu.show {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 90%;
        max-width: 400px;
        max-height: 70vh;
        border-radius: 12px;
        border: 2px solid #ecf0f1;
    }
}
</style>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('antrianForm');
    const submitBtn = document.getElementById('submitBtn');

    // Initialize Custom Dropdowns
    initCustomDropdowns();

    // Disable semua readonly input
    const readonlyInputs = document.querySelectorAll('.readonly-input');
    readonlyInputs.forEach(function(input) {
        input.addEventListener('click', function(e) {
            e.preventDefault();
            return false;
        });
        
        input.addEventListener('focus', function(e) {
            e.preventDefault();
            this.blur();
            return false;
        });
    });

    // Prevent double submission
    if (form) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.classList.add('btn-loading');
            submitBtn.innerHTML = '<i class="fas fa-spinner"></i> Memproses...';
        });
    }

    // Set minimum date untuk tanggal antrian
    const tanggalInput = document.getElementById('tanggal');
    if (tanggalInput) {
        const today = new Date().toISOString().split('T')[0];
        tanggalInput.setAttribute('min', today);
    }

    // Close alert functionality
    document.querySelectorAll('.alert-close').forEach(button => {
        button.addEventListener('click', function() {
            this.parentElement.style.display = 'none';
        });
    });

    function initCustomDropdowns() {
        const dropdowns = document.querySelectorAll('.custom-dropdown');
        let backdrop = null;

        dropdowns.forEach(dropdown => {
            const trigger = dropdown.querySelector('.dropdown-trigger');
            const menu = dropdown.querySelector('.dropdown-menu');
            const options = dropdown.querySelectorAll('.dropdown-option');
            const hiddenInput = dropdown.querySelector('input[type="hidden"]');
            const searchInput = dropdown.querySelector('.search-input');
            const dropdownText = dropdown.querySelector('.dropdown-text');

            // Create backdrop for mobile
            if (!backdrop) {
                backdrop = document.createElement('div');
                backdrop.className = 'dropdown-backdrop';
                document.body.appendChild(backdrop);
            }

            // Set initial state
            const currentValue = hiddenInput.value;
            if (currentValue) {
                const selectedOption = dropdown.querySelector(`[data-value="${currentValue}"]`);
                if (selectedOption) {
                    selectOption(selectedOption, dropdown);
                }
            }

            // Trigger click
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Close other dropdowns
                closeAllDropdowns();
                
                // Toggle current dropdown
                const isOpen = menu.classList.contains('show');
                if (!isOpen) {
                    openDropdown(dropdown);
                } else {
                    closeDropdown(dropdown);
                }
            });

            // Option click
            options.forEach(option => {
                option.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    selectOption(this, dropdown);
                    closeDropdown(dropdown);
                });
            });

            // Search functionality
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase();
                    options.forEach(option => {
                        const text = option.textContent.toLowerCase();
                        if (text.includes(query)) {
                            option.classList.remove('hidden');
                        } else {
                            option.classList.add('hidden');
                        }
                    });
                });

                searchInput.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            // Backdrop click
            backdrop.addEventListener('click', function() {
                closeAllDropdowns();
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.custom-dropdown')) {
                closeAllDropdowns();
            }
        });

        // Close dropdowns on escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAllDropdowns();
            }
        });

        function openDropdown(dropdown) {
            const trigger = dropdown.querySelector('.dropdown-trigger');
            const menu = dropdown.querySelector('.dropdown-menu');
            const searchInput = dropdown.querySelector('.search-input');

            trigger.classList.add('active');
            menu.classList.add('show');
            
            // Show backdrop on mobile
            if (window.innerWidth <= 768) {
                backdrop.classList.add('show');
            }

            // Focus search input
            if (searchInput) {
                setTimeout(() => {
                    searchInput.focus();
                }, 100);
            }
        }

        function closeDropdown(dropdown) {
            const trigger = dropdown.querySelector('.dropdown-trigger');
            const menu = dropdown.querySelector('.dropdown-menu');
            const searchInput = dropdown.querySelector('.search-input');

            trigger.classList.remove('active');
            menu.classList.remove('show');
            backdrop.classList.remove('show');

            // Clear search
            if (searchInput) {
                searchInput.value = '';
                dropdown.querySelectorAll('.dropdown-option').forEach(option => {
                    option.classList.remove('hidden');
                });
            }
        }

        function closeAllDropdowns() {
            dropdowns.forEach(dropdown => {
                closeDropdown(dropdown);
            });
        }

        function selectOption(option, dropdown) {
            const value = option.getAttribute('data-value');
            const text = option.getAttribute('data-text') || option.textContent.trim();
            const hiddenInput = dropdown.querySelector('input[type="hidden"]');
            const dropdownText = dropdown.querySelector('.dropdown-text');

            // Update hidden input
            hiddenInput.value = value;

            // Update display text
            dropdownText.textContent = text;
            dropdownText.classList.remove('placeholder');

            // Update selected state
            dropdown.querySelectorAll('.dropdown-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            option.classList.add('selected');

            // Remove invalid state
            const trigger = dropdown.querySelector('.dropdown-trigger');
            trigger.classList.remove('is-invalid');
        }
    }
});
</script>
@endsection