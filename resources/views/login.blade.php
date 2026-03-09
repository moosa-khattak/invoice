@extends("layout.layout")

@section("content")
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Login to InvoicePro</h2>

        <!-- Display validation errors -->
        @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Display session success messages -->
        @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
        @endif

        <!-- Display session error messages -->
        @if (session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ route("login.save") }}" method="post" class="space-y-4">
            @csrf

            <x-input
                name="email"
                label="Email"
                type="email"
                placeholder="Enter your email"
                required />

            <x-input
                name="password"
                label="Password"
                type="password"
                placeholder="Enter your password"
                required />

            <button type="submit"
                class="w-full py-2 px-4 bg-teal-600 text-white rounded-lg font-medium hover:bg-teal-700 transition cursor-pointer">
                Login
            </button>
            <a href="{{ route("login.google") }}"
                class=" flex justify-center py-2 px-4 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700  transition cursor-pointer">
                Login with Google
            </a>
            <a href="{{ route("login.github") }}"
                class=" flex justify-center py-2 px-4 bg-black text-white rounded-lg font-medium hover:bg-gray-700  transition cursor-pointer">
                Login with Github
            </a>
        </form>

        <div class="mt-4 text-center text-sm text-gray-600">
            <a href="{{ route('invoice.create') }}" class="text-teal-600 hover:underline cursor-pointer">Back to Invoice</a>
        </div>
    </div>
</div>
@endsection