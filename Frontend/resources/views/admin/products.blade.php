@extends('layouts.admin')

@section('title', 'Product Management')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Product Management</h1>
        <button id="openModalBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center transform hover:scale-105">
            <i class="fa-solid fa-plus mr-2"></i> Add New Product
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
                        <th scope="col" class="px-6 py-4">Image</th>
                        <th scope="col" class="px-6 py-4">Product Name</th>
                        <th scope="col" class="px-6 py-4">Menu</th>
                        <th scope="col" class="px-6 py-4">Price</th>
                        <th scope="col" class="px-6 py-4">Stock</th>
                        <th scope="col" class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr class="bg-white border-b hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded-lg shadow-md">
                            </td>
                            <th scope="row" class="px-6 py-4 font-semibold text-gray-900 whitespace-nowrap">
                                {{ $product->name }}
                            </th>
                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1.5 rounded-full">{{ $product->menu->name ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-800">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($product->stock > 10)
                                    <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1.5 rounded-full">{{ $product->stock }} In Stock</span>
                                @elseif($product->stock > 0)
                                    <span class="bg-yellow-100 text-yellow-800 text-sm font-medium px-3 py-1.5 rounded-full">{{ $product->stock }} Low Stock</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-sm font-medium px-3 py-1.5 rounded-full">Out of Stock</span>
                                @endif
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
                            <td colspan="6" class="text-center py-16 text-gray-500">
                                <i class="fa-solid fa-box-open fa-4x mb-4 text-gray-300"></i>
                                <p class="text-xl font-semibold">No Products Found</p>
                                <p class="mt-2">Click "Add New Product" to get started.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($products->hasPages())
            <div class="p-4 bg-gray-50 border-t">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <div id="createProductModal" class="fixed inset-0 bg-black bg-opacity-60 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-3xl m-4 transform transition-all" id="modalContent">
            <div class="flex justify-between items-center mb-6 pb-4 border-b">
                 <h2 class="text-2xl font-bold text-gray-800">Add a New Product</h2>
                 <button id="closeModalBtn" class="text-gray-400 hover:text-red-600 text-3xl transition-colors duration-200">&times;</button>
            </div>
           
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="fa-solid fa-tag text-gray-400"></i>
                                </div>
                                <input type="text" name="name" value="{{ old('name') }}" class="block w-full rounded-lg border-gray-300 shadow-sm pl-10 focus:border-blue-500 focus:ring-2 focus:ring-blue-200" required>
                            </div>
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="menu_id" class="block text-sm font-medium text-gray-700 mb-1">Menu Category</label>
                            <select id="menu_id" name="menu_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200" required>
                                <option value="" disabled selected>Choose a category</option>
                                @foreach($menus as $menu)
                                    <option value="{{ $menu->id }}" {{ old('menu_id') == $menu->id ? 'selected' : '' }}>{{ $menu->name }}</option>
                                @endforeach
                            </select>
                            @error('menu_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                         <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="price" value="{{ old('price') }}" class="block w-full rounded-lg border-gray-300 shadow-sm pl-10 focus:border-blue-500 focus:ring-2 focus:ring-blue-200" placeholder="50000" required>
                            </div>
                            @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                             <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="fa-solid fa-boxes-stacked text-gray-400"></i>
                                </div>
                                <input type="number" name="stock" value="{{ old('stock') }}" class="block w-full rounded-lg border-gray-300 shadow-sm pl-10 focus:border-blue-500 focus:ring-2 focus:ring-blue-200" required>
                            </div>
                            @error('stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
                        <div class="mt-1 flex justify-center rounded-lg border-2 border-dashed border-gray-300 px-6 pt-5 pb-6">
                            <div class="space-y-1 text-center" id="fileUploadPrompt">
                                <i class="fa-solid fa-cloud-arrow-up mx-auto h-12 w-12 text-gray-400"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="image" class="relative cursor-pointer rounded-md bg-white font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2">
                                        <span>Upload a file</span>
                                        <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                            <div id="imagePreviewContainer" class="hidden text-center">
                                <img id="imagePreview" src="" alt="Image Preview" class="mx-auto h-32 w-32 object-cover rounded-lg shadow-sm">
                                <button type="button" id="removeImageBtn" class="mt-2 text-sm text-red-600 hover:text-red-800 font-semibold">Remove Image</button>
                            </div>
                        </div>
                        @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                <div class="mt-8 pt-6 border-t flex justify-end space-x-4">
                    <button type="button" id="cancelBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                        Save Product
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
        const createProductModal = document.getElementById('createProductModal');
        
        const imageInput = document.getElementById('image');
        const fileUploadPrompt = document.getElementById('fileUploadPrompt');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const removeImageBtn = document.getElementById('removeImageBtn');

        function openModal() { createProductModal.classList.remove('hidden'); }
        function closeModal() { createProductModal.classList.add('hidden'); }

        openModalBtn.addEventListener('click', openModal);
        closeModalBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        createProductModal.addEventListener('click', function(event) {
            if (event.target === createProductModal) {
                closeModal();
            }
        });

        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.setAttribute('src', e.target.result);
                    fileUploadPrompt.classList.add('hidden');
                    imagePreviewContainer.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        removeImageBtn.addEventListener('click', function() {
            imageInput.value = '';
            imagePreview.setAttribute('src', '');
            imagePreviewContainer.classList.add('hidden');
            fileUploadPrompt.classList.remove('hidden');
        });

        @if($errors->any())
            openModal();
        @endif
    </script>
    @endpush
@endsection