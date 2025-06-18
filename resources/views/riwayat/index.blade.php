@extends('layouts.main')

@section('content')
<div class="main-content">
    <!-- Mobile Header -->
    <div class="mobile-page-header">
        <h2 class="page-title">Riwayat Kunjungan Pasien</h2>
    </div>

    <!-- Filter Dropdown -->
    <div class="sort-container">
        <div class="custom-dropdown">
            <div class="dropdown-header" onclick="toggleFilterDropdown()">
                <div class="dropdown-selected">
                    <i class="fas fa-filter dropdown-icon"></i>
                    <span id="selected-text">
                        @if(request('poli') == 'Umum')
                            Poli Umum
                        @elseif(request('poli') == 'Kebidanan') 
                            Poli Kebidanan
                        @else
                            Semua Poli
                        @endif
                    </span>
                </div>
                <i class="fas fa-chevron-down dropdown-arrow"></i>
            </div>
            <div class="dropdown-menu" id="filter-dropdown-menu">
                <div class="dropdown-item {{ !request('poli') ? 'active' : '' }}" onclick="selectOption('all', 'Semua Poli')">
                    <i class="fas fa-list-ul item-icon"></i>
                    <span>Semua Poli</span>
                    @if(!request('poli'))
                        <i class="fas fa-check check-icon"></i>
                    @endif
                </div>
                <div class="dropdown-item {{ request('poli') == 'Umum' ? 'active' : '' }}" onclick="selectOption('Umum', 'Poli Umum')">
                    <i class="fas fa-stethoscope item-icon"></i>
                    <span>Poli Umum</span>
                    @if(request('poli') == 'Umum')
                        <i class="fas fa-check check-icon"></i>
                    @endif
                </div>
                <div class="dropdown-item {{ request('poli') == 'Kebidanan' ? 'active' : '' }}" onclick="selectOption('Kebidanan', 'Poli Kebidanan')">
                    <i class="fas fa-baby item-icon"></i>
                    <span>Poli Kebidanan</span>
                    @if(request('poli') == 'Kebidanan')
                        <i class="fas fa-check check-icon"></i>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-container">
        <div class="card mobile-card">
            <div class="card-header mobile-header">
                <h5 class="mobile-title">
                    <i class="fas fa-history me-2"></i>Riwayat Kunjungan
                </h5>
                <span class="mobile-count badge bg-light text-dark">
                    {{ $riwayatAntrian->total() ?? 0 }}
                </span>
            </div>
            <div class="card-body p-0">
                @if(isset($riwayatAntrian) && $riwayatAntrian->count() > 0)
                
                {{-- Desktop Table --}}
                <div class="desktop-view">
                    <div class="table-container">
                        <table class="table table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>No Antrian</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>Gender</th>
                                    <th>HP</th>
                                    <th>Poli</th>
                                    <th>Tgl Antrian</th>
                                    <th>Status</th>
                                    <th>Dokter</th>
                                    <th>Tgl Dibuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($riwayatAntrian as $key => $antrian)
                                <tr>
                                    <td>{{ $riwayatAntrian->firstItem() + $key }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $antrian->no_antrian }}</span>
                                    </td>
                                    <td>{{ $antrian->name ?? '-' }}</td>
                                    <td>{{ $antrian->user->address ?? '-' }}</td>
                                    <td>{{ $antrian->gender ?? '-' }}</td>
                                    <td>{{ $antrian->phone ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $antrian->poli == 'Umum' ? 'primary' : 'success' }}">
                                            {{ $antrian->poli }}
                                        </span>
                                    </td>
                                    <td>{{ $antrian->formatted_tanggal }}</td>
                                    <td>
                                        <span class="badge bg-{{ $antrian->status_badge }}">
                                            {{ ucfirst($antrian->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $antrian->doctor->nama ?? '-' }}</td>
                                    <td>
                                        {{ $antrian->created_at->format('d/m/Y') }}<br>
                                        <small>{{ $antrian->created_at->format('H:i') }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Mobile Cards --}}
                <div class="mobile-view">
                    <div class="mobile-cards">
                        @foreach ($riwayatAntrian as $antrian)
                        <div class="mobile-card-item">
                            <div class="mobile-card-header">
                                <span class="badge bg-info">{{ $antrian->no_antrian }}</span>
                                <span class="badge bg-{{ $antrian->status_badge }}">
                                    {{ ucfirst($antrian->status) }}
                                </span>
                            </div>
                            <div class="mobile-card-body">
                                <h6 class="patient-name">{{ $antrian->name ?? '-' }}</h6>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ $antrian->user->address ?? '-' }}</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-venus-mars"></i>
                                        <span>{{ $antrian->gender ?? '-' }}</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-phone"></i>
                                        <span>{{ $antrian->phone ?? '-' }}</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-hospital"></i>
                                        <span class="badge bg-{{ $antrian->poli == 'Umum' ? 'primary' : 'success' }}">
                                            {{ $antrian->poli }}
                                        </span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>{{ $antrian->formatted_tanggal }}</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-user-md"></i>
                                        <span>{{ $antrian->doctor->nama ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="pagination-container">
                    <small class="text-muted">
                        {{ $riwayatAntrian->firstItem() }}-{{ $riwayatAntrian->lastItem() }} 
                        dari {{ $riwayatAntrian->total() }}
                    </small>
                    <div class="pagination-links">
                        {{ $riwayatAntrian->appends(request()->query())->links() }}
                    </div>
                </div>

                @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <h5>Belum Ada Riwayat</h5>
                    <p>Riwayat kunjungan akan muncul setelah Anda mengambil antrian.</p>
                    <a href="{{ route('antrian.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Ambil Antrian
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="backdrop-overlay" id="backdrop-overlay"></div>

<style>
/* Base */
.main-content {
    padding: 0;
    background: #f8f9fa;
    min-height: 100vh;
    z-index: 1000;
}

/* Mobile Header */
.mobile-page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px 15px;
    color: white;
    position: sticky;
    top: 0;
    z-index: 1100;
}

.page-title {
    font-size: 1.4rem;
    font-weight: 700;
    margin: 0;
    text-align: center;
    color: white;
}

/* Filter Container */
.sort-container {
    padding: 15px;
    background: white;
    border-bottom: 1px solid #e3e6f0;
    position: sticky;
    top: 85px;
    z-index: 1500;
}

.custom-dropdown {
    position: relative;
    z-index: 1501;
}

.dropdown-header {
    background: #f8f9fa;
    border: 2px solid #e3e6f0;
    border-radius: 15px;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    z-index: 1502;
}

.dropdown-header:hover {
    border-color: #667eea;
    background: white;
}

.dropdown-header.active {
    border-color: #667eea;
    background: white;
    border-radius: 15px 15px 0 0;
}

.dropdown-selected {
    display: flex;
    align-items: center;
    flex: 1;
}

.dropdown-icon {
    color: #667eea;
    margin-right: 12px;
    font-size: 1.1rem;
}

.dropdown-selected span {
    font-size: 1rem;
    font-weight: 600;
    color: #495057;
}

.dropdown-arrow {
    color: #6c757d;
    font-size: 0.9rem;
    transition: transform 0.3s;
}

.dropdown-header.active .dropdown-arrow {
    transform: rotate(180deg);
    color: #667eea;
}

/* Dropdown Menu */
.sort-container .dropdown-menu {
    position: absolute;
    top: calc(100% - 2px);
    left: 0;
    right: 0;
    background: white;
    border: 2px solid #667eea;
    border-top: none;
    border-radius: 0 0 15px 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    z-index: 1503;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s;
}

.sort-container .dropdown-menu.show {
    opacity: 1;
    visibility: visible;
}

.sort-container .dropdown-item {
    padding: 15px 20px;
    display: flex;
    align-items: center;
    cursor: pointer;
    border-bottom: 1px solid #f1f3f4;
    background: white;
}

.sort-container .dropdown-item:last-child {
    border-bottom: none;
}

.sort-container .dropdown-item:hover {
    background: #f8f9fa;
}

.sort-container .dropdown-item.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.item-icon {
    margin-right: 12px;
    font-size: 1rem;
    width: 20px;
    text-align: center;
    color: #6c757d;
}

.sort-container .dropdown-item.active .item-icon {
    color: white;
}

.sort-container .dropdown-item span {
    flex: 1;
    font-size: 0.95rem;
    font-weight: 500;
}

.check-icon {
    color: #28a745;
    font-size: 0.9rem;
}

.sort-container .dropdown-item.active .check-icon {
    color: white;
}

/* Backdrop */
.backdrop-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.1);
    z-index: 1450;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s;
}

.backdrop-overlay.show {
    opacity: 1;
    visibility: visible;
}

/* Content */
.content-container {
    padding: 15px;
    padding-top: 0;
    z-index: 1000;
}

.mobile-card {
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    border: none;
    background: white;
}

.mobile-header {
    background: linear-gradient(135deg, #495057 0%, #343a40 100%);
    padding: 18px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.mobile-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: white;
    margin: 0;
}

.mobile-count {
    font-size: 0.9rem;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 20px;
}

/* Desktop Table */
.desktop-view {
    display: block;
}

.mobile-view {
    display: none;
}

.table-container {
    max-height: 600px;
    overflow: auto;
    border: 1px solid #dee2e6;
    border-radius: 8px;
}

.table thead th {
    background-color: #343a40;
    color: white;
    font-size: 0.85rem;
    font-weight: 600;
    padding: 12px 8px;
    border-bottom: 2px solid #495057;
    position: sticky;
    top: 0;
    z-index: 10;
}

.table tbody td {
    font-size: 0.85rem;
    padding: 10px 8px;
    border-bottom: 1px solid #dee2e6;
}

.badge {
    font-size: 0.75rem;
    padding: 4px 8px;
    border-radius: 4px;
}

/* Mobile Cards */
.mobile-cards {
    padding: 10px;
    max-height: 70vh;
    overflow-y: auto;
}

.mobile-card-item {
    background: white;
    border: 1px solid #e3e6f0;
    border-radius: 12px;
    margin-bottom: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    overflow: hidden;
}

.mobile-card-header {
    background: #f8f9fa;
    padding: 12px 15px;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.mobile-card-body {
    padding: 15px;
}

.patient-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 12px;
    border-bottom: 2px solid #3498db;
    padding-bottom: 8px;
}

.info-grid {
    display: grid;
    gap: 12px;
}

.info-item {
    display: flex;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f1f3f4;
}

.info-item:last-child {
    border-bottom: none;
}

.info-item i {
    width: 16px;
    color: #6c757d;
    margin-right: 10px;
}

.info-item span {
    color: #212529;
    font-size: 0.9rem;
}

/* Pagination */
.pagination-container {
    padding: 15px 20px;
    background: #f8f9fa;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: between;
    align-items: center;
    flex-wrap: wrap;
}

/* Empty State */
.empty-state {
    padding: 60px 20px;
    text-align: center;
    background: #f8f9fa;
}

.empty-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #e3e6f0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}

.empty-icon i {
    font-size: 2rem;
    color: #6c757d;
}

.empty-state h5 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #495057;
    margin-bottom: 12px;
}

.empty-state p {
    color: #6c757d;
    font-size: 0.95rem;
    margin-bottom: 25px;
}

.btn {
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: linear-gradient(45deg, #3498db, #2980b9);
    color: white;
}

/* Mobile Responsive */
@media (max-width: 991.98px) {
    .desktop-view { display: none; }
    .mobile-view { display: block; }
    
    .sort-container { z-index: 1500; }
    .custom-dropdown { z-index: 1501; }
    .dropdown-header { z-index: 1502; }
    .sort-container .dropdown-menu { z-index: 1503; }
    .backdrop-overlay { z-index: 1450; }
    .mobile-page-header { z-index: 1100; }
}

@media (max-width: 576px) {
    .mobile-page-header { padding: 15px 12px; }
    .page-title { font-size: 1.2rem; }
    .sort-container { padding: 12px; top: 75px; }
    .dropdown-header { padding: 12px 15px; border-radius: 12px; }
    .dropdown-header.active { border-radius: 12px 12px 0 0; }
    .sort-container .dropdown-menu { border-radius: 0 0 12px 12px; }
    .content-container { padding: 12px; }
    .mobile-header { padding: 15px; flex-direction: column; gap: 8px; }
    .mobile-title { font-size: 1rem; }
    .mobile-cards { padding: 8px; max-height: 60vh; }
    .mobile-card-item { margin-bottom: 12px; border-radius: 10px; }
    .patient-name { font-size: 1rem; }
    .pagination-container { padding: 12px 15px; }
}

@media (max-width: 375px) {
    .page-title { font-size: 1.1rem; }
    .sort-container { padding: 10px; top: 65px; }
    .dropdown-header { padding: 10px 12px; border-radius: 10px; }
    .dropdown-header.active { border-radius: 10px 10px 0 0; }
    .sort-container .dropdown-menu { border-radius: 0 0 10px 10px; }
    .content-container { padding: 10px; }
    .mobile-cards { padding: 5px; }
    .patient-name { font-size: 0.95rem; }
}
</style>

<script>
let isFilterDropdownOpen = false;

function toggleFilterDropdown() {
    const dropdown = document.getElementById('filter-dropdown-menu');
    const header = document.querySelector('.dropdown-header');
    const backdrop = document.getElementById('backdrop-overlay');
    
    isFilterDropdownOpen = !isFilterDropdownOpen;
    
    if (isFilterDropdownOpen) {
        dropdown.classList.add('show');
        header.classList.add('active');
        backdrop.classList.add('show');
    } else {
        dropdown.classList.remove('show');
        header.classList.remove('active');
        backdrop.classList.remove('show');
    }
}

function closeFilterDropdown() {
    const dropdown = document.getElementById('filter-dropdown-menu');
    const header = document.querySelector('.dropdown-header');
    const backdrop = document.getElementById('backdrop-overlay');
    
    if (dropdown && header) {
        dropdown.classList.remove('show');
        header.classList.remove('active');
        backdrop.classList.remove('show');
        isFilterDropdownOpen = false;
    }
}

function selectOption(value, text) {
    document.getElementById('selected-text').textContent = text;
    
    const items = document.querySelectorAll('.sort-container .dropdown-item');
    items.forEach(item => {
        item.classList.remove('active');
        const checkIcon = item.querySelector('.check-icon');
        if (checkIcon) checkIcon.remove();
    });
    
    const selectedItem = [...items].find(item => item.textContent.trim().includes(text));
    if (selectedItem) {
        selectedItem.classList.add('active');
        const checkIcon = document.createElement('i');
        checkIcon.className = 'fas fa-check check-icon';
        selectedItem.appendChild(checkIcon);
    }
    
    closeFilterDropdown();
    
    setTimeout(() => {
        sortRiwayat(value);
    }, 150);
}

function sortRiwayat(poli) {
    let url = '{{ route('riwayat.index') }}';
    if (poli !== 'all') {
        url += '?poli=' + poli;
    }
    window.location.href = url;
}

document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isFilterDropdownOpen) {
            closeFilterDropdown();
        }
    });
});

document.addEventListener('click', function(e) {
    const filterDropdown = document.querySelector('.custom-dropdown');
    
    if (isFilterDropdownOpen && !filterDropdown.contains(e.target)) {
        closeFilterDropdown();
    }
});

document.getElementById('backdrop-overlay').addEventListener('click', function() {
    if (isFilterDropdownOpen) {
        closeFilterDropdown();
    }
});

window.addEventListener('scroll', function() {
    if (isFilterDropdownOpen) {
        closeFilterDropdown();
    }
});
</script>
@endsection