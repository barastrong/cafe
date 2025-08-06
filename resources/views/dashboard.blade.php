@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 sm:p-6 lg:p-8">
    <div class="mb-8 text-center">
        <h1 class="text-4xl font-bold text-gray-800">Our Products</h1>
        <p class="mt-2 text-lg text-gray-600">Discover our signature collection of coffee and delights.</p>
    </div>

    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach ($products as $product)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:-translate-y-2 transition-transform duration-300 ease-in-out">
                    
                    {{-- Gambar Produk --}}
                    <div class="relative">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-56 object-cover">
                        
                        @if($product->menu)
                            <span class="absolute top-3 right-3 bg-blue-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-md">
                                {{ $product->menu->name }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="p-5">
                        <h3 class="text-xl font-bold text-gray-900 truncate">{{ $product->name }}</h3>
                        <p class="text-lg font-semibold text-gray-700 mt-2">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    </div>

                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-lg shadow-md">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">No Products Available</h3>
            <p class="mt-1 text-sm text-gray-500">Please check back later, we are preparing our best products for you.</p>
        </div>
    @endif
</div>
@endsection