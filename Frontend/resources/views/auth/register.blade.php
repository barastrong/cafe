<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

  <form method="POST" action="{{ route('register') }}" class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    @csrf
  <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>

    <!-- Name -->
    <div class="mb-4">
      <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
      <input id="name" type="text" name="name" required autofocus autocomplete="name"
        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
      <!-- Validation Error -->
      <!-- <p class="text-sm text-red-600 mt-1">Error message here</p> -->
    </div>

    <!-- Email -->
    <div class="mb-4">
      <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
      <input id="email" type="email" name="email" required autocomplete="username"
        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
    </div>

    <!-- Password -->
    <div class="mb-4">
      <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
      <input id="password" type="password" name="password" required autocomplete="new-password"
        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
    </div>

    <!-- Confirm Password -->
    <div class="mb-6">
      <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
      <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
    </div>

    <!-- Footer -->
    <div class="flex items-center justify-between">
      <a href="/login" class="text-sm text-indigo-600 hover:underline">Already registered?</a>
      <button type="submit"
        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">Register</button>
    </div>
  </form>

</body>
</html>
