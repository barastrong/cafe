@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Admin Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card Total Products -->
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center space-x-4">
            <div class="bg-blue-100 p-4 rounded-full">
                <i class="fa-solid fa-box-archive text-blue-500 text-3xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Products</p>
                <p class="text-3xl font-bold text-gray-800">{{ $productCount }}</p>
            </div>
        </div>

        <!-- Card Total Users -->
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center space-x-4">
            <div class="bg-green-100 p-4 rounded-full">
                <i class="fa-solid fa-users text-green-500 text-3xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Users</p>
                <p class="text-3xl font-bold text-gray-800">{{ $userCount }}</p>
            </div>
        </div>
        
        <!-- Anda bisa menambahkan card lain di sini -->
    </div>
@endsection