{{-- resources/views/admin/dashboard.blade.php --}}

<!-- Quick Stats for Admin Dashboard -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Pending Review</p>
                <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Donor::where('status', 'pending')->count() }}</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.donors.index') }}?status=pending" 
               class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">
                Lihat Semua →
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-thumbs-up text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Siap Donor</p>
                <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Donor::where('status', 'approved')->count() }}</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.donors.index') }}?status=approved" 
               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Lihat Semua →
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Hari Ini</p>
                <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Donor::whereDate('created_at', today())->count() }}</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.donors.index') }}?date={{ date('Y-m-d') }}" 
               class="text-green-600 hover:text-green-800 text-sm font-medium">
                Lihat Detail →
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-tint text-red-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Donor</p>
                <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Donor::where('status', 'completed')->count() }}</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.donors.index') }}" 
               class="text-red-600 hover:text-red-800 text-sm font-medium">
                Kelola Donor →
            </a>
        </div>
    </div>
</div>
