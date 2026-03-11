@extends('layout.layout')

@section('title', 'Page Not Found')

@section('content')
<div class="min-h-[70vh] flex flex-col items-center justify-center text-center px-4">
    <!-- 404 Watermark -->
    <h1 class="text-9xl md:text-[12rem] font-black text-gray-100 select-none drop-shadow-sm">404</h1>

    <!-- Content -->
    <div class="-mt-12 md:-mt-20 z-10 flex flex-col items-center">
        <div class="bg-indigo-50 p-4 rounded-full mb-6">
            <svg class="h-10 w-10 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <h2 class="text-3xl font-bold text-gray-900 tracking-tight sm:text-4xl">Oops! Page not found.</h2>
        <p class="text-gray-500 mt-4 max-w-md text-lg">
          Sorry, the page you are looking for doesn’t exist or may have been moved.
        </p>
        <p class="text-gray-500 mt-4 max-w-md text-lg">
          Please check the URL or return to the homepage.
        </p>

        <!-- Back Button -->
        <a href="/" class="mt-8 bg-gray-900 hover:bg-black text-white px-8 py-3.5 rounded-xl font-medium shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 inline-flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Home
        </a>
    </div>
</div>
@endsection