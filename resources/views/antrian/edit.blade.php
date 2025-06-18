@extends('layouts.main')

@section('title', 'Edit Antrian')

@section('content')
<!-- Main Content -->
<main class="main-content">
    <!-- Page Header -->
    <div class="page-header animate">
        <h1><i class="fas fa-edit"></i> Edit Antrian</h1>
        <p>Ubah informasi antrian Anda</p>
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
        <form action="{{ route('antrian.update', $antrian->id) }}" method="POST" id="editAntrianForm">
            @csrf
            @method('PUT')
            
            <!-- Queue Information Section -->
            <div class="form-section">
                <h6 class="form-section-title">
                    <i class="fas fa-ticket-alt"></i>
                    Informasi Antrian
                </h6>
                
                <div class="form-grid">
                    <!-- Nomor Antrian - READONLY -->
                    <div class="form-group full-width">
                        <label for="no_antrian" class="form-label">Nomor Antrian</label>
                        <input type="text" 
                               class="form-input readonly-input" 
                               id="no_antrian" 
                               value="{{ $antrian->no_antrian }}" 
                               readonly 
                               tabindex="-1">
                        <small class="form-help">Nomor antrian tidak dapat diubah</small>
                    </div>
                </div>
            </div>

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
                               value="{{ old('name', $antrian->name) }}" 
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
                               value="{{ old('phone', $antrian->phone) }}" 
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
                               value="{{ old('gender', $antrian->gender) }}" 
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
                    <!-- Poli - EDITABLE -->
                    <div class="form-group">
                        <label for="poli" class="form-label">Poli</label>
                        <select class="form-select @error('poli') is-invalid @enderror" 
                                id="poli" 
                                name="poli" 
                                required>
                            <option value="">-- Pilih Poli --</option>
                            @foreach($poli as $p)
                                <option value="{{ $p->nama }}" 
                                        {{ old('poli', $antrian->poli) == $p->nama ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('poli')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Dokter - EDITABLE -->
                    <div class="form-group">
                        <label for="doctor_id" class="form-label">Dokter</label>
                        <select class="form-select @error('doctor_id') is-invalid @enderror" 
                                id="doctor_id" 
                                name="doctor_id" 
                                required>
                            <option value="">-- Pilih Dokter --</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->doctor_id }}" 
                                        data-specialization="{{ $doctor->spesialisasi }}"
                                        {{ old('doctor_id', $antrian->doctor_id) == $doctor->doctor_id ? 'selected' : '' }}>
                                    {{ $doctor->nama }} - {{ $doctor->spesialisasi }}
                                </option>
                            @endforeach
                        </select>
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
                               value="{{ old('tanggal', $antrian->tanggal->format('Y-m-d')) }}" 
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
                <button type="submit" class="btn btn-primary btn-lg" id="updateBtn">
                    <i class="fas fa-save"></i>
                    Update Antrian
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
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
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

/* Enhanced Mobile Responsive */
@media (max-width: 768px) {
    .page-header {
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .page-header h1 {
        font-size: 1.5rem;
    }
    
    .form-grid {
        grid-template-columns: 1fr !important;
        gap: 15px;
    }
    
    .form-card {
        padding: 20px;
        margin: 0 10px;
        border-radius: 10px;
    }
    
    .form-section {
        margin-bottom: 25px;
    }
    
    .form-section-title {
        font-size: 1rem;
        margin-bottom: 15px;
        padding-bottom: 8px;
    }
    
    .form-input,
    .form-select {
        padding: 14px 15px;
        font-size: 16px; /* Prevents zoom on iOS */
        border-radius: 8px;
    }
    
    .form-actions {
        flex-direction: column;
        gap: 12px;
        margin-top: 25px;
    }
    
    .btn-lg {
        padding: 16px 20px;
        font-size: 16px;
        width: 100%;
        justify-content: center;
    }
    
    .alert {
        margin: 0 10px 20px 10px;
        padding: 15px;
    }
    
    .alert-content {
        font-size: 14px;
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

/* Additional Mobile Fixes */
@media (max-width: 480px) {
    .main-content {
        padding: 15px 10px;
    }
    
    .page-header {
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .page-header h1 {
        font-size: 1.3rem;
        margin-bottom: 5px;
    }
    
    .page-header p {
        font-size: 13px;
    }
    
    .form-card {
        padding: 15px;
        margin: 0 5px;
    }
    
    .form-section-title {
        font-size: 0.95rem;
        gap: 8px;
    }
    
    .form-label {
        font-size: 13px;
        margin-bottom: 6px;
    }
    
    .form-input,
    .form-select {
        padding: 12px;
        font-size: 16px;
    }
    
    .form-help {
        font-size: 11px;
    }
    
    .form-error {
        font-size: 11px;
    }
    
    .btn-lg {
        padding: 14px 18px;
        font-size: 15px;
    }
    
    .alert {
        margin: 0 5px 15px 5px;
        padding: 12px;
    }
    
    .alert-close {
        top: 10px;
        right: 10px;
        font-size: 16px;
    }
}

/* Tablet Responsive */
@media (min-width: 769px) and (max-width: 1024px) {
    .form-grid {
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 18px;
    }
    
    .form-card {
        padding: 25px;
    }
    
    .main-content {
        padding: 25px;
    }
}
</style>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editAntrianForm');
    const updateBtn = document.getElementById('updateBtn');
    const doctorSelect = document.getElementById('doctor_id');

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

    // Show doctor specialization when selected
    if (doctorSelect) {
        doctorSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const specialization = selectedOption.getAttribute('data-specialization');
                console.log('Dokter dipilih:', selectedOption.text);
            }
        });
    }

    // Prevent double submission
    if (form) {
        form.addEventListener('submit', function() {
            updateBtn.disabled = true;
            updateBtn.classList.add('btn-loading');
            updateBtn.innerHTML = '<i class="fas fa-spinner"></i> Memproses...';
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
});
</script>
@endsection