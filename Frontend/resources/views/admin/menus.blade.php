@extends('layouts.admin')

@section('title', 'Menu Management')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Menu Management</h1>
        <button id="openModalBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center transform hover:scale-105">
            <i class="fa-solid fa-plus mr-2"></i> Add New Menu
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-4 mb-6 rounded-r-lg shadow-sm" role="alert">
            <div class="flex">
                <div class="py-1"><i class="fa-solid fa-circle-check fa-lg text-green-500 mr-3"></i></div>
                <div>
                    <p class="font-bold">Success</p>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th scope="col" class="px-6 py-4">Menu Name</th>
                        <th scope="col" class="px-6 py-4">Slug</th>
                        <th scope="col" class="px-6 py-4">Total Products</th>
                        <th scope="col" class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($menus as $menu)
                        <tr class="bg-white border-b hover:bg-gray-50 transition-colors duration-200">
                            <th scope="row" class="px-6 py-4 font-semibold text-gray-900 whitespace-nowrap">
                                {{ $menu->name }}
                            </th>
                            <td class="px-6 py-4">
                                <code class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded">{{ $menu->slug }}</code>
                            </td>
                            <td class="px-6 py-4">
                                {{ $menu->products_count }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900 p-2 rounded-full hover:bg-indigo-50" title="Edit">
                                    <i class="fa-solid fa-pen-to-square fa-lg"></i>
                                </a>
                                <a href="#" class="text-red-600 hover:text-red-900 p-2 rounded-full hover:bg-red-50 ml-2" title="Delete">
                                    <i class="fa-solid fa-trash-can fa-lg"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-16 text-gray-500">
                                <i class="fa-solid fa-folder-open fa-4x mb-4 text-gray-300"></i>
                                <p class="text-xl font-semibold">No Menus Found</p>
                                <p class="mt-2">Click "Add New Menu" to create your first menu category.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($menus->hasPages())
            <div class="p-4 bg-gray-50 border-t">
                {{ $menus->links() }}
            </div>
        @endif
    </div>

    <div id="createMenuModal" class="fixed inset-0 bg-black bg-opacity-60 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md m-4 transform transition-all" id="modalContent">
            <div class="flex justify-between items-center mb-6 pb-4 border-b">
                 <h2 class="text-2xl font-bold text-gray-800">Add a New Menu</h2>
                 <button id="closeModalBtn" class="text-gray-400 hover:text-red-600 text-3xl transition-colors duration-200">&times;</button>
            </div>
           
            <form action="{{ route('admin.menus.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Menu Name</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fa-solid fa-tags text-gray-400"></i>
                            </div>
                            <input type="text" name="name" value="{{ old('name') }}" class="block w-full rounded-lg border-gray-300 shadow-sm pl-10 focus:border-blue-500 focus:ring-2 focus:ring-blue-200" placeholder="e.g., Coffee, Pastry, Non-Coffee" required>
                        </div>
                        @error('name') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                <div class="mt-8 pt-6 border-t flex justify-end space-x-4">
                    <button type="button" id="cancelBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                        Save Menu
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const openModalBtn = document.getElementById('openModalBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const createMenuModal = document.getElementById('createMenuModal');

        function openModal() { createMenuModal.classList.remove('hidden'); }
        function closeModal() { createMenuModal.classList.add('hidden'); }

        openModalBtn.addEventListener('click', openModal);
        closeModalBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        createMenuModal.addEventListener('click', function(event) {
            if (event.target === createMenuModal) {
                closeModal();
            }
        });

        @if($errors->any())
            openModal();
        @endif
    </script>
    @endpush
@endsection