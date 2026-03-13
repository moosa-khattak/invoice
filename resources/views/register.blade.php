@extends("layout.layout")
@section('title' , "Register Form")
@section("content")
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Register for InvoicePro</h2>

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

        <form action="{{ route('register.save') }}" method="post" class="space-y-4">
            @csrf

            <x-input
                name="name"
                label="Name"
                placeholder="Enter your full name"
                required />

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
                placeholder="Create a password"
                required />

            <x-input
                name="password_confirmation"
                label="Confirm Password"
                type="password"
                placeholder="Repeat your password"
                required />

            <button type="submit"
                class="w-full py-2 px-4 bg-teal-600 text-white rounded-lg font-medium hover:bg-teal-700 transition cursor-pointer">
                Register
            </button>
        </form>

        <div class="mt-4 text-center text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="text-teal-600 hover:underline cursor-pointer">Login</a>
        </div>

        <div class="mt-2 text-center text-sm text-gray-600">
            <a href="{{ route('invoice.create') }}" class="text-teal-600 hover:underline cursor-pointer">Back to Invoice</a>
        </div>
    </div>
</div>
@endsection