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

        <form action="{{ route("login.save") }}" method="post" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
                <input type="email" name="email" id="email" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>

            <!-- Remember Me -->
            <!-- <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="mr-2">
                <label for="remember" class="text-gray-700">Remember Me</label>
            </div> -->

            <button type="submit"
                class="w-full py-2 px-4 bg-teal-600 text-white rounded-lg font-medium hover:bg-teal-700 transition cursor-pointer">
                Login
            </button>
        </form>

        <div class="mt-4 text-center text-sm text-gray-600">
            <a href="{{ route('invoice.create') }}" class="text-teal-600 hover:underline cursor-pointer">Back to Invoice</a>
        </div>
    </div>
</div>
@endsection