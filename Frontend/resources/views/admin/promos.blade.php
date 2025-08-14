@extends('layouts.admin')

@section('title', 'Promo Management')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Promo Management</h1>
        <button id="openModalBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center transform hover:scale-105">
            <i class="fa-solid fa-plus mr-2"></i> Add New Promo
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-4 mb-6 rounded-r-lg shadow-sm" role="alert">
            <div class="flex"><div class="py-1"><i class="fa-solid fa-circle-check fa-lg text-green-500 mr-3"></i></div><div><p class="font-bold">Success</p><p>{{ session('success') }}</p></div></div>
        </div>
    @endif

    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th scope="col" class="px-6 py-4">Code</th>
                        <th scope="col" class="px-6 py-4">Type</th>
                        <th scope="col" class="px-6 py-4">Value</th>
                        <th scope="col" class="px-6 py-4">Validity Period</th>
                        <th scope="col" class="px-6 py-4">Status</th>
                        <th scope="col" class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($promos as $promo)
                        <tr class="bg-white border-b hover:bg-gray-50 transition-colors duration-200">
                            <th scope="row" class="px-6 py-4 font-semibold text-gray-900 whitespace-nowrap">
                                <code class="bg-yellow-100 text-yellow-800 font-bold px-2 py-1 rounded">{{ $promo->code }}</code>
                            </th>
                            <td class="px-6 py-4 capitalize">{{ $promo->type }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800">
                                @if($promo->type === 'percent')
                                    {{ $promo->value }}%
                                @else
                                    Rp {{ number_format($promo->value, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $promo->start_date->format('d M Y') }} - {{ $promo->end_date->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                @if($promo->is_active && $promo->end_date->isFuture())
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1.5 rounded-full">Active</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-3 py-1.5 rounded-full">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900 p-2 rounded-full hover:bg-indigo-50" title="Edit"><i class="fa-solid fa-pen-to-square fa-lg"></i></a>
                                <a href="#" class="text-red-600 hover:text-red-900 p-2 rounded-full hover:bg-red-50 ml-2" title="Delete"><i class="fa-solid fa-trash-can fa-lg"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-16 text-gray-500">
                                <i class="fa-solid fa-tags fa-4x mb-4 text-gray-300"></i>
                                <p class="text-xl font-semibold">No Promos Found</p>
                                <p class="mt-2">Click "Add New Promo" to create your first promotion.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($promos->hasPages())
            <div class="p-4 bg-gray-50 border-t">{{ $promos->links() }}</div>
        @endif
    </div>

    <div id="createPromoModal" class="fixed inset-0 bg-black bg-opacity-60 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-3xl m-4 transform transition-all" id="modalContent">
            <div class="flex justify-between items-center mb-6 pb-4 border-b"><h2 class="text-2xl font-bold text-gray-800">Add a New Promo</h2><button id="closeModalBtn" class="text-gray-400 hover:text-red-600 text-3xl transition-colors duration-200">&times;</button></div>
           
            <form action="{{ route('admin.promos.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Promo Code</label>
                            <input type="text" name="code" value="{{ old('code') }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200" placeholder="e.g., KOPIHEMAT" required>
                            @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Promo Type</label>
                            <select name="type" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200" required>
                                <option value="" disabled selected>Choose type</option>
                                <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Percentage</option>
                            </select>
                            @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="value" class="block text-sm font-medium text-gray-700 mb-1">Value (Rp or %)</label>
                            <input type="number" name="value" value="{{ old('value') }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200" placeholder="e.g., 10000 or 15" required>
                            @error('value') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="min_purchase" class="block text-sm font-medium text-gray-700 mb-1">Minimum Purchase (Optional)</label>
                            <input type="number" name="min_purchase" value="{{ old('min_purchase') }}" class="block w-full rounded-lg border-g-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200" placeholder="e.g., 50000">
                             @error('min_purchase') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200" required>
                             @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200" required>
                            @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center">
                            <input id="is_active" name="is_active" type="checkbox" value="1" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active" class="ml-3 block text-sm font-medium text-gray-900">Activate this promo immediately</label>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 pt-6 border-t flex justify-end space-x-4">
                    <button type="button" id="cancelBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-lg transition-colors duration-200">Cancel</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105">Save Promo</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const openModalBtn = document.getElementById('openModalBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const createPromoModal = document.getElementById('createPromoModal');

        function openModal() { createPromoModal.classList.remove('hidden'); }
        function closeModal() { createPromoModal.classList.add('hidden'); }

        openModalBtn.addEventListener('click', openModal);
        closeModalBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        createPromoModal.addEventListener('click', function(event) {
            if (event.target === createPromoModal) { closeModal(); }
        });

        @if($errors->any())
            openModal();
        @endif
    </script>
    @endpush
@endsection