@extends('dashboardlayout.app')

@section('page-title', 'Daftar Artikel')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-newspaper mr-3 text-blue-600"></i>Kelola Artikel
                    </h1>
                    <p class="text-gray-600">Kelola semua artikel blog Anda</p>
                </div>
                <a href="{{ route('admin.articles.create') }}" 
                   class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                    <i class="fas fa-plus mr-2"></i>Tambah Artikel
                </a>
            </div>
        </div>

        <!-- Articles Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-newspaper mr-2"></i>Artikel
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-user mr-2"></i>Penulis
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-tags mr-2"></i>Status & Kategori
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-calendar mr-2"></i>Tanggal
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-cog mr-2"></i>Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($articles as $article)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- Article Info with Image -->
                            <td class="px-6 py-4">
                                <div class="flex items-start space-x-4">
                                    <!-- Featured Image -->
                                    <div class="flex-shrink-0">
                                        @if($article->featured_image)
                                            <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                                 alt="{{ $article->title }}"
                                                 class="w-16 h-16 rounded-lg object-cover border border-gray-200 shadow-sm"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200" style="display: none;">
                                                <i class="fas fa-image text-gray-400 text-xl"></i>
                                            </div>
                                        @else
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                                <i class="fas fa-image text-gray-400 text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Article Details -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h3 class="text-sm font-medium text-gray-900 mb-1">
                                                    {{ Str::limit($article->title, 50) }}
                                                    @if($article->is_featured)
                                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <i class="fas fa-star mr-1"></i>Featured
                                                        </span>
                                                    @endif
                                                </h3>
                                                <p class="text-sm text-gray-500 mb-2">
                                                    {{ Str::limit($article->excerpt ?: strip_tags($article->content), 80) }}
                                                </p>
                                                <div class="flex items-center space-x-4 text-xs text-gray-400">
                                                    <span class="flex items-center">
                                                        <i class="fas fa-eye mr-1"></i>
                                                        {{ $article->views ?? 0 }} views
                                                    </span>
                                                    @if($article->tags && is_array($article->tags))
                                                        <span class="flex items-center">
                                                            <i class="fas fa-tags mr-1"></i>
                                                            {{ implode(', ', array_slice($article->tags, 0, 3)) }}
                                                            @if(count($article->tags) > 3)
                                                                <span class="ml-1 text-gray-400">+{{ count($article->tags) - 3 }}</span>
                                                            @endif
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Author -->
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-blue-600 font-medium text-sm">
                                            {{ substr($article->author, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $article->author }}
                                        </div>
                                        @if($article->author_title)
                                            <div class="text-xs text-gray-500">
                                                {{ $article->author_title }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Status & Category -->
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <!-- Status -->
                                    @if($article->status === 'published')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Published
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Draft
                                        </span>
                                    @endif
                                    
                                    <!-- Category -->
                                    @if($article->category)
                                        <div class="text-xs text-gray-600">
                                            <i class="fas fa-folder mr-1"></i>
                                            {{ ucwords(str_replace('-', ' ', $article->category)) }}
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Date -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    @if($article->status === 'published' && $article->published_at)
                                        <div class="text-green-600 font-medium">
                                            {{ $article->published_at->format('d M Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $article->published_at->format('H:i') }}
                                        </div>
                                    @else
                                        <div class="text-yellow-600 font-medium">
                                            {{ $article->status === 'draft' ? 'Draft' : 'Belum dipublikasikan' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Dibuat: {{ $article->created_at->format('d M Y') }}
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <!-- View -->

                                    
                                    <!-- Quick Actions -->
                                    <div class="flex items-center space-x-1">
                                        <!-- Toggle Status -->
                                        <form action="{{ route('admin.articles.toggle-status', $article->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="p-1 rounded transition-colors {{ $article->status === 'published' ? 'text-yellow-600 hover:text-yellow-800' : 'text-green-600 hover:text-green-800' }}"
                                                    title="{{ $article->status === 'published' ? 'Jadikan Draft' : 'Publikasikan' }}">
                                                <i class="fas {{ $article->status === 'published' ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                            </button>
                                        </form>
                                        
                                        <!-- Toggle Featured -->
                                        <form action="{{ route('admin.articles.toggle-featured', $article->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="p-1 rounded transition-colors {{ $article->is_featured ? 'text-yellow-600 hover:text-yellow-800' : 'text-gray-400 hover:text-yellow-600' }}"
                                                    title="{{ $article->is_featured ? 'Hapus dari Featured' : 'Jadikan Featured' }}">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <!-- Edit -->
                                    <a href="{{ route('admin.articles.edit', $article->id) }}" 
                                       class="text-green-600 hover:text-green-800 p-1 rounded transition-colors"
                                       title="Edit Artikel">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Delete -->
                                    <form action="{{ route('admin.articles.destroy', $article->id) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 p-1 rounded transition-colors"
                                                title="Hapus Artikel">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-newspaper text-gray-400 text-2xl"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada artikel</h3>
                                    <p class="text-gray-500 mb-4">Mulai dengan membuat artikel pertama Anda</p>
                                    <a href="{{ route('admin.articles.create') }}" 
                                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-plus mr-2"></i>Buat Artikel
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    document.getElementById('searchInput').addEventListener('input', filterArticles);
    document.getElementById('statusFilter').addEventListener('change', filterArticles);
    document.getElementById('categoryFilter').addEventListener('input', filterArticles);
    document.getElementById('featuredFilter').addEventListener('change', filterArticles);
});

function filterArticles() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const status = document.getElementById('statusFilter').value;
    const category = document.getElementById('categoryFilter').value.toLowerCase();
    const featured = document.getElementById('featuredFilter').value;
    
    const rows = document.querySelectorAll('.article-row');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const matchSearch = search === '' || row.dataset.search.includes(search);
        const matchStatus = status === '' || row.dataset.status === status;
        const matchCategory = category === '' || row.dataset.category.includes(category);
        const matchFeatured = featured === '' || row.dataset.featured === featured;
        
        if (matchSearch && matchStatus && matchCategory && matchFeatured) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Show/hide empty state
    const emptyState = document.getElementById('emptyState');
    if (emptyState) {
        emptyState.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
    }
    
    // Update counters
    updateVisibleCount(visibleCount);
}

function updateVisibleCount(count) {
    const articleCountElement = document.getElementById('articleCount');
    const visibleCountElement = document.getElementById('visibleCount');
    
    if (articleCountElement) {
        articleCountElement.textContent = `Total: ${count} artikel`;
    }
    
    if (visibleCountElement) {
        visibleCountElement.textContent = count;
    }
}

// Export Excel function
function exportToExcel() {
    const table = document.getElementById('articlesTable');
    const rows = table.querySelectorAll('tbody tr.article-row:not([style*="display: none"])');
    
    if (rows.length === 0) {
        alert('Tidak ada data untuk diekspor!');
        return;
    }
    
    let csvContent = 'Judul,Penulis,Status,Kategori,Views,Tanggal\n';
    
    rows.forEach(row => {
        const title = row.querySelector('.article-title').textContent.trim();
        const author = row.querySelector('.article-author').textContent.trim();
        const status = row.dataset.status;
        const categoryElement = row.querySelector('.article-category');
        const category = categoryElement ? categoryElement.textContent.replace('🏷️', '').trim() : '';
        const viewsElement = row.querySelector('td:nth-child(4) .fa-eye').parentElement;
        const views = viewsElement ? viewsElement.textContent.replace('👁️', '').replace(' views', '').trim() : '0';
        const dateElement = row.querySelector('td:nth-child(
        const dateElement = row.querySelector('td:nth-child(4) div:first-child');
        const date = dateElement ? dateElement.textContent.trim() : '';
        
        csvContent += `"${title}","${author}","${status}","${category}","${views}","${date}"\n`;
    });
    
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.setAttribute('hidden', '');
    a.setAttribute('href', url);
    a.setAttribute('download', 'data_artikel_' + new Date().toISOString().split('T')[0] + '.csv');
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    
    // Show success message
    showNotification('File berhasil didownload!', 'success');
}

function refreshData() {
    // Show loading state
    showNotification('Memuat ulang data...', 'info');
    setTimeout(() => {
        location.reload();
    }, 500);
}

// Notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
    
    const colors = {
        success: 'bg-green-500 text-white',
        error: 'bg-red-500 text-white',
        info: 'bg-blue-500 text-white',
        warning: 'bg-yellow-500 text-white'
    };
    
    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        info: 'fas fa-info-circle',
        warning: 'fas fa-exclamation-triangle'
    };
    
    notification.className += ` ${colors[type]}`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="${icons[type]} mr-2"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 300);
    }, 3000);
}

// Add smooth scrolling for better UX
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Add keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl + N = New Article
    if (e.ctrlKey && e.key === 'n') {
        e.preventDefault();
        window.location.href = "{{ route('admin.articles.create') }}";
    }
    
    // Ctrl + R = Refresh
    if (e.ctrlKey && e.key === 'r') {
        e.preventDefault();
        refreshData();
    }
    
    // Ctrl + E = Export
    if (e.ctrlKey && e.key === 'e') {
        e.preventDefault();
        exportToExcel();
    }
    
    // Escape = Clear filters
    if (e.key === 'Escape') {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('categoryFilter').value = '';
        document.getElementById('featuredFilter').value = '';
        filterArticles();
    }
});

// Add loading states for buttons
document.querySelectorAll('button, a').forEach(element => {
    element.addEventListener('click', function() {
        if (this.dataset.loading !== 'false') {
            const originalText = this.innerHTML;
            const isButton = this.tagName === 'BUTTON';
            
            if (isButton && !this.type === 'submit') {
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
                this.disabled = true;
                
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 1000);
            }
        }
    });
});

// Add tooltips for better UX
const tooltipElements = document.querySelectorAll('[title]');
tooltipElements.forEach(element => {
    element.addEventListener('mouseenter', function() {
        const tooltip = document.createElement('div');
        tooltip.className = 'absolute z-50 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-lg pointer-events-none';
        tooltip.textContent = this.getAttribute('title');
        tooltip.id = 'tooltip';
        
        document.body.appendChild(tooltip);
        
        const rect = this.getBoundingClientRect();
        tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
        tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';
    });
    
    element.addEventListener('mouseleave', function() {
        const tooltip = document.getElementById('tooltip');
        if (tooltip) {
            tooltip.remove();
        }
    });
});
</script>

<style>
/* Custom scrollbar for table */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Smooth transitions */
.article-row {
    transition: all 0.2s ease-in-out;
}

.article-row:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

/* Loading animation */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Custom badge styles */
.badge-status {
    position: relative;
    overflow: hidden;
}

.badge-status::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.badge-status:hover::before {
    left: 100%;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .article-row td {
        padding: 12px 8px;
    }
    
    .article-row .flex {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .article-row .flex-shrink-0 {
        margin-bottom: 8px;
    }
}

/* Print styles */
@media print {
    .no-print {
        display: none !important;
    }
    
    .article-row {
        break-inside: avoid;
    }
    
    table {
        font-size: 12px;
    }
}

/* Focus styles for accessibility */
button:focus,
a:focus,
input:focus,
select:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

/* Dark mode support (optional) */
@media (prefers-color-scheme: dark) {
    .dark-mode .bg-white {
        background-color: #1f2937;
        color: #f9fafb;
    }
    
    .dark-mode .text-gray-900 {
        color: #f9fafb;
    }
    
    .dark-mode .text-gray-600 {
        color: #d1d5db;
    }
    
    .dark-mode .border-gray-200 {
        border-color: #374151;
    }
}
</style>
@endsection
