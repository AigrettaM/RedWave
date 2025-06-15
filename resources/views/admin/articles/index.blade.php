@extends('dashboardlayout.app')

@section('title', 'Dashboard - Manajemen Artikel')
@section('page-title', 'Manajemen Artikel')

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
        <h2 class="text-dark fw-bolder">Manajemen Artikel</h2>
    </div>

    <!-- Search Section -->
    <div class="row mb-4">
        <div class="col-md-8 offset-md-2">
            <div class="search-wrapper">
                <div class="search-box">
                    <div class="search-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <input type="text" 
                           class="search-input" 
                           placeholder="Cari berdasarkan judul artikel..." 
                           id="searchInput">
                    <button class="clear-btn" type="button" id="clearSearch" title="Bersihkan pencarian">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Articles Table -->
    <div class="card shadow-sm">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover align-middle mb-0" id="articleTable">
                <thead class="table-light">
                    <tr>
                        <th class="text-center fw-semibold" style="width: 50px;">No</th>
                        <th class="fw-semibold" style="width: 300px;">Data Artikel</th>
                        <th class="fw-semibold" style="width: 150px;">Penulis</th>
                        <th class="text-center fw-semibold" style="width: 100px;">Status</th>
                        <th class="text-center fw-semibold" style="width: 120px;">Kategori</th>
                        <th class="text-center fw-semibold" style="width: 100px;">Featured</th>
                        <th class="text-center fw-semibold" style="width: 120px;">Tgl Publish</th>
                        <th class="text-center fw-semibold" style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $index => $article)
                    <tr class="border-bottom table-row">
                        <td class="text-center">
                            <span class="badge bg-light text-dark fw-normal">{{ $index + 1 }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-start">
                                <div class="article-image me-3">
                                    @if($article->featured_image)
                                        <img src="{{ Storage::url($article->featured_image) }}" 
                                             class="rounded" 
                                             style="width: 50px; height: 50px; object-fit: cover;"
                                             alt="{{ $article->title }}">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold text-dark article-title">{{ Str::limit($article->title, 45) }}</div>
                                    <small class="text-muted d-block">{{ $article->slug }}</small>
                                    @if($article->excerpt)
                                        <div class="text-muted small mt-1">{{ Str::limit($article->excerpt, 70) }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($article->author_avatar)
                                    <img src="{{ Storage::url($article->author_avatar) }}" 
                                         class="rounded-circle me-2" 
                                         style="width: 32px; height: 32px; object-fit: cover;"
                                         alt="{{ $article->author_name }}">
                                @else
                                    <div class="avatar-circle bg-info bg-opacity-10 me-2">
                                        <i class="fas fa-user text-info"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-medium">{{ $article->author_name }}</div>
                                    @if($article->author_title)
                                        <small class="text-muted">{{ $article->author_title }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($article->is_published)
                                <span class="badge bg-success text-white">
                                    <i class="fas fa-check-circle me-1"></i>Published
                                </span>
                            @else
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-edit me-1"></i>Draft
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary text-white">
                                {{ ucfirst(str_replace('-', ' ', $article->category)) }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($article->is_featured)
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-star me-1"></i>Featured
                                </span>
                            @else
                                <span class="badge bg-light text-muted">
                                    <i class="far fa-star me-1"></i>Normal
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($article->published_at)
                                <div class="text-dark small">{{ $article->published_at->format('d-m-Y') }}</div>
                                <small class="text-muted">{{ $article->published_at->format('H:i') }}</small>
                            @else
                                <span class="text-muted small">Belum dipublikasikan</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <!-- CRUD ACTIONS -->
                            <div class="crud-actions">
                                <!-- CREATE (Tambah) -->
                                <div class="action-group mb-2">
                                    <span class="action-label">Create:</span>
                                    <a href="{{ route('admin.articles.create') }}" 
                                       class="btn btn-success btn-sm" 
                                       title="Tambah Data Artikel Baru">
                                        <i class="fas fa-plus me-1"></i>Add
                                    </a>
                                </div>

                                <!-- READ (View) -->
                                <div class="action-group mb-2">
                                    <span class="action-label">Read:</span>
                                    <a href="{{ route('admin.articles.show', $article->original ?? $article->id) }}" 
                                       class="btn btn-info btn-sm" 
                                       title="Lihat Detail Artikel">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                </div>

                                <!-- UPDATE (Edit) -->
                                <div class="action-group mb-2">
                                    <span class="action-label">Update:</span>
                                    <a href="{{ route('admin.articles.edit', $article->original ?? $article->id) }}" 
                                       class="btn btn-warning btn-sm" 
                                       title="Edit Data Artikel">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                </div>

                                <!-- DELETE (Hapus) -->
                                <div class="action-group">
                                    <span class="action-label">Delete:</span>
                                    <form action="{{ route('admin.articles.destroy', $article->original ?? $article->id) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-danger btn-sm" 
                                                title="Hapus Data Artikel"
                                                onclick="return confirm('Yakin ingin menghapus data artikel {{ $article->title }}? Data yang sudah dihapus tidak dapat dikembalikan!')">
                                            <i class="fas fa-trash me-1"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyState">
                        <td colspan="8" class="text-center py-5">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-database"></i>
                                </div>
                                <h5 class="text-muted mb-2">Tidak Ada Data Artikel</h5>
                                <p class="text-muted mb-3">Belum ada data artikel yang tersimpan dalam database</p>
                                <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Tambah Data Pertama
                                </a>
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
                Tidak ditemukan data artikel dengan kata kunci "<span id="searchKeyword" class="fw-bold"></span>"
            </p>
            <button class="btn btn-outline-primary btn-sm" onclick="clearSearch()">
                <i class="fas fa-arrow-left me-1"></i>Kembali ke Semua Data
            </button>
        </div>
    </div>
</div>

<style>
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
    margin-bottom: 1rem;
}

.search-box {
    position: relative;
    display: flex;
    align-items: center;
    background: #ffffff;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.search-box:focus-within {
    border-color: #667eea;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
    transform: translateY(-2px);
}

.search-icon {
    padding: 15px 20px;
    color: #6c757d;
    background: transparent;
}

.search-input {
    flex: 1;
    border: none;
    outline: none;
    padding: 15px 20px;
    font-size: 1rem;
    background: transparent;
    color: #495057;
}

.search-input::placeholder {
    color: #adb5bd;
}

.clear-btn {
    background: none;
    border: none;
    padding: 15px 20px;
    color: #6c757d;
    cursor: pointer;
    border-radius: 0 6px 6px 0;
    transition: all 0.2s ease;
}

.clear-btn:hover {
    background: #f8f9fa;
    color: #dc3545;
}

/* CRUD ACTIONS STYLING */
.crud-actions {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 12px;
    min-width: 180px;
    text-align: left;
}

.action-group {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}

.action-group:last-child {
    margin-bottom: 0;
}

.action-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    min-width: 50px;
}

.btn-xs {
    padding: 0.25rem 0.4rem;
    font-size: 0.7rem;
    border-radius: 4px;
}

/* Table Enhancements */
.avatar-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.empty-state {
    padding: 3rem 2rem;
}

.empty-state-icon {
    width: 100px;
    height: 100px;
    margin: 0 auto 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.05);
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.2s ease;
}

.card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.badge {
    font-size: 0.75rem;
    padding: 0.5em 0.75em;
}

.table th {
    border-bottom: 2px solid #dee2e6;
    padding: 15px 12px;
    background-color: #f8f9fa !important;
    font-weight: 600;
    color: #2c3e50;
}

.table td {
    padding: 15px 12px;
    vertical-align: middle;
}

.article-title {
    color: #2c3e50 !important;
    font-size: 0.95rem;
}

/* No Results Card */
.no-results-card {
    background: #ffffff;
    border: 2px dashed #dee2e6;
    border-radius: 15px;
    margin-top: 20px;
    padding: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.no-results-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
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
    
    .search-input, .search-icon, .clear-btn {
        padding: 12px 15px;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .crud-actions {
        min-width: 150px;
        padding: 8px;
    }
    
    .action-label {
        font-size: 0.7rem;
        min-width: 40px;
    }
}

@media (max-width: 576px) {
    .container {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .search-box {
        border-radius: 6px;
    }
    
    .crud-actions {
        min-width: 130px;
    }
}

/* Animation for loading states */
@keyframes pulse-search {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.searching {
    animation: pulse-search 1.5s ease-in-out infinite;
}

/* Button hover effects */
.crud-actions .btn {
    transition: all 0.2s ease;
}

.crud-actions .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clearButton = document.getElementById('clearSearch');
    const table = document.getElementById('articleTable');
    const tbody = table.getElementsByTagName('tbody')[0];
    const rows = Array.from(tbody.getElementsByTagName('tr'));
    const noResultsCard = document.getElementById('noSearchResults');
    const searchKeyword = document.getElementById('searchKeyword');
    const emptyState = document.getElementById('emptyState');
    
    // Data rows (exclude empty state)
    const dataRows = rows.filter(row => row.cells.length > 1);
    const totalArticles = dataRows.length;

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
                const titleCell = row.querySelector('.article-title');
                const titleText = titleCell.textContent.toLowerCase();
                
                if (filter === '' || titleText.includes(filter)) {
                    row.style.display = '';
                    visibleCount++;
                    hasResults = true;
                    
                    // Update nomor urut
                    const numberCell = row.cells[0].querySelector('.badge');
                    numberCell.textContent = visibleCount;
                    
                    // Highlight hasil pencarian
                    if (filter !== '') {
                        highlightText(titleCell, filter);
                    } else {
                        removeHighlight(titleCell);
                    }
                } else {
                    row.style.display = 'none';
                    removeHighlight(titleCell);
                }
            });

            // Remove searching animation
            setTimeout(() => {
                tbody.classList.remove('searching');
            }, 300);

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
                emptyState.style.display = (totalArticles === 0 && filter === '') ? '' : 'none';
            }
        }, 150);
    }

    // Highlight function
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
