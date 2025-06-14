@extends('dashboardlayout.app')

@section('title', 'Dashboard - Your App')
@section('page-title', 'Manajemen Data User')

@section('content')
<div class="container mt-4">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="text-center mb-4">
        <h2 class="text-dark fw-bolder">Manajemen Data User</h2>
    </div>

    <!-- Search Section -->
    <div class="row mb-4">
        <div class="col-md-6 offset-md-6">
            <div class="search-wrapper">
                <div class="search-box">
                    <div class="search-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <input type="text" 
                           class="search-input" 
                           placeholder="Cari berdasarkan nama user..." 
                           id="searchInput">
                    <button class="clear-btn" type="button" id="clearSearch" title="Bersihkan pencarian">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover align-middle mb-0" id="userTable">
                <thead class="table-light">
                    <tr>
                        <th class="text-center fw-semibold" style="width: 50px;">No</th>
                        <th class="fw-semibold" style="width: 200px;">Nama</th>
                        <th class="text-center fw-semibold" style="width: 120px;">Kode Donor</th>
                        <th class="text-center fw-semibold" style="width: 100px;">Gol. Darah</th>
                        <th class="text-center fw-semibold" style="width: 80px;">Rhesus</th>
                        <th class="text-center fw-semibold" style="width: 120px;">Jenis Kelamin</th>
                        <th class="fw-semibold" style="width: 140px;">Telepon</th>
                        <th class="text-center fw-semibold" style="width: 140px;">Tgl Lahir</th>
                        <th class="text-center fw-semibold" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($profiles as $index => $profile)
                    <tr class="border-bottom table-row">
                        <td class="text-center">
                            <span class="badge bg-light text-dark fw-normal">{{ $index + 1 }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle bg-primary bg-opacity-10 me-3">
                                    <i class="fas fa-user text-primary"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark user-name">{{ $profile->name }}</div>
                                    <small class="text-muted">ID: {{ $profile->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($profile->donor_code)
                                <span class="badge bg-success text-white">{{ $profile->donor_code }}</span>
                            @else
                                <span class="badge bg-secondary">Belum ada</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-danger text-white fw-bold">{{ $profile->blood_type }}</span>
                        </td>
                        <td class="text-center">
                            <!-- DIPERBAIKI: Kondisi rhesus -->
                            <span class="badge {{ $profile->rhesus == 'POSITIF' ? 'bg-success' : 'bg-warning' }} text-white">
                                {{ $profile->rhesus == 'POSITIF' ? '+' : '-' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $profile->gender == 'Laki-laki' ? 'bg-info' : 'bg-pink' }} text-white">
                                <i class="fas {{ $profile->gender == 'Laki-laki' ? 'fa-mars' : 'fa-venus' }} me-1"></i>
                                {{ $profile->gender }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-phone text-muted me-2"></i>
                                <span class="text-nowrap">{{ $profile->telephone }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="text-dark">{{ \Carbon\Carbon::parse($profile->birth_date)->format('d-m-Y') }}</div>
                            <small class="text-muted">({{ \Carbon\Carbon::parse($profile->birth_date)->age }} tahun)</small>
                        </td>
                        <td class="text-center">
                            <form action="{{ route('profiles.destroy', $profile->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data {{ $profile->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm delete-btn" title="Hapus Data">
                                    <i class="fas fa-trash me-1"></i>Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyState">
                        <td colspan="9" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-users text-muted mb-3" style="font-size: 3rem;"></i>
                                <h5 class="text-muted">Tidak Ada Data User</h5>
                                <p class="text-muted mb-0">Belum ada data user yang terdaftar dalam sistem</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- No Search Results -->
    <div class="no-results-card" id="noSearchResults" style="display: none;">
        <div class="text-center py-5">
            <div class="no-results-icon">
                <i class="fas fa-search"></i>
            </div>
            <h5 class="text-muted mb-2">Tidak Ada Hasil Ditemukan</h5>
            <p class="text-muted mb-3">
                Tidak ditemukan user dengan kata kunci "<span id="searchKeyword" class="fw-bold"></span>"
            </p>
            <button class="btn btn-outline-primary btn-sm" onclick="clearSearch()">
                <i class="fas fa-arrow-left me-1"></i>Kembali ke Semua Data
            </button>
        </div>
    </div>
</div>

<style>
.bg-pink {
    background-color: #e91e63 !important;
}

/* Header Styles */
.text-center h2 {
    color: #2c3e50;
    font-size: 2.5rem;
    font-weight: 900 !important;
    margin-bottom: 0;
}

/* Search Wrapper */
.search-wrapper {
    position: relative;
}

.search-box {
    position: relative;
    display: flex;
    align-items: center;
    background: #ffffff;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.search-box:focus-within {
    border-color: #0d6efd;
    box-shadow: 0 4px 16px rgba(13, 110, 253, 0.15);
    transform: translateY(-1px);
}

.search-icon {
    padding: 12px 16px;
    color: #6c757d;
    background: transparent;
    border-right: 1px solid #e9ecef;
}

.search-input {
    flex: 1;
    border: none;
    outline: none;
    padding: 12px 16px;
    font-size: 0.95rem;
    background: transparent;
    color: #495057;
}

.search-input::placeholder {
    color: #adb5bd;
}

.clear-btn {
    background: none;
    border: none;
    padding: 12px 16px;
    color: #6c757d;
    cursor: pointer;
    border-radius: 0 10px 10px 0;
    transition: all 0.2s ease;
}

.clear-btn:hover {
    background: #f8f9fa;
    color: #dc3545;
}

.search-info {
    margin-top: 8px;
    padding-left: 4px;
}

/* No Results Card */
.no-results-card {
    background: #ffffff;
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    margin-top: 20px;
    padding: 20px;
}

.no-results-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #adb5bd;
    font-size: 2rem;
}

/* Table Enhancements */
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.empty-state {
    padding: 2rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.03);
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.2s ease;
}

.card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
}

.badge {
    font-size: 0.75rem;
}

.table th {
    border-bottom: 2px solid #dee2e6;
    padding: 12px 8px;
    background-color: #f8f9fa !important;
    font-weight: 600;
}

.table td {
    padding: 12px 8px;
    vertical-align: middle;
}

.delete-btn:hover {
    transform: scale(1.05);
    transition: all 0.2s ease;
}

.user-name {
    color: #2c3e50 !important;
}

/* Highlight Search Results */
.highlight {
    background: linear-gradient(120deg, #fff3cd 0%, #fff3cd 100%);
    background-size: 100% 0.2em;
    background-repeat: no-repeat;
    background-position: 0 88%;
    font-weight: 600;
    color: #856404;
    padding: 2px 4px;
    border-radius: 4px;
    transition: all 0.3s ease;
}

/* Responsive Design */
@media (max-width: 768px) {
    .text-center h2 {
        font-size: 2rem;
    }
    
    .search-wrapper {
        margin-bottom: 0;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .avatar-circle {
        width: 32px;
        height: 32px;
    }
    
    .delete-btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .search-input {
        padding: 10px 12px;
        font-size: 0.9rem;
    }
    
    .search-icon, .clear-btn {
        padding: 10px 12px;
    }
}

@media (max-width: 576px) {
    .container {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .search-box {
        border-radius: 8px;
    }
    
    .results-summary {
        padding: 10px 12px;
    }
}

/* Animation for loading states */
@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.searching {
    animation: pulse 1.5s ease-in-out infinite;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clearButton = document.getElementById('clearSearch');
    const table = document.getElementById('userTable');
    const tbody = table.getElementsByTagName('tbody')[0];
    const rows = Array.from(tbody.getElementsByTagName('tr'));
    const noResultsCard = document.getElementById('noSearchResults');
    const searchKeyword = document.getElementById('searchKeyword');
    const emptyState = document.getElementById('emptyState');
    
    // Data rows (exclude empty state)
    const dataRows = rows.filter(row => row.cells.length > 1);
    const totalUsers = dataRows.length;

    // Search functionality with debouncing
    let searchTimeout;
    function performSearch() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const filter = searchInput.value.toLowerCase().trim();
            let visibleCount = 0;
            let hasResults = false;

            // Add searching animation
            tbody.classList.add('searching');

            dataRows.forEach((row, index) => {
                const nameCell = row.querySelector('.user-name');
                const nameText = nameCell.textContent.toLowerCase();
                
                if (filter === '' || nameText.includes(filter)) {
                    row.style.display = '';
                    visibleCount++;
                    hasResults = true;
                    
                    // Update nomor urut
                    const numberCell = row.cells[0].querySelector('.badge');
                    numberCell.textContent = visibleCount;
                    
                    // Highlight hasil pencarian
                    if (filter !== '') {
                        highlightText(nameCell, filter);
                    } else {
                        removeHighlight(nameCell);
                    }
                } else {
                    row.style.display = 'none';
                    removeHighlight(nameCell);
                }
            });

            // Remove searching animation
            setTimeout(() => {
                tbody.classList.remove('searching');
            }, 300);

            // DIPERBAIKI: Hapus bagian showingText yang error

            // Show/hide no results message
            if (filter !== '' && !hasResults) {
                table.style.display = 'none';
                searchKeyword.textContent = filter;
                noResultsCard.style.display = 'block';
            } else {
                table.style.display = '';
                noResultsCard.style.display = 'none';
            }

            // Show/hide empty state
            if (emptyState) {
                emptyState.style.display = (totalUsers === 0 && filter === '') ? '' : 'none';
            }
        }, 150);
    }

    // Highlight function with improved styling
    function highlightText(element, searchTerm) {
        const originalText = element.getAttribute('data-original') || element.textContent;
        element.setAttribute('data-original', originalText);
        
        const regex = new RegExp(`(${searchTerm})`, 'gi');
        const highlightedText = originalText.replace(regex, '<span class="highlight">$1</span>');
        element.innerHTML = highlightedText;
    }

    // Remove highlight function
    function removeHighlight(element) {
        const originalText = element.getAttribute('data-original');
        if (originalText) {
            element.textContent = originalText;
            element.removeAttribute('data-original');
        }
    }

    // Clear search function
    function clearSearch() {
        searchInput.value = '';
        performSearch();
        searchInput.focus();
    }

    // Event listeners
    searchInput.addEventListener('input', performSearch);
    
    searchInput.addEventListener('keyup', function(e) {
        if (e.key === 'Escape') {
            clearSearch();
        }
    });

    // Show/hide clear button based on input
    searchInput.addEventListener('input', function() {
        clearButton.style.opacity = this.value ? '1' : '0.5';
    });

    clearButton.addEventListener('click', clearSearch);

    // Global function for reset button
    window.clearSearch = clearSearch;

    // Auto-focus on search input
    searchInput.focus();

    // Initialize clear button opacity
    clearButton.style.opacity = '0.5';
});
</script>
@endsection
