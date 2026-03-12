@extends('layout.layout')
@section('title' , "Create Invoice")
@section('content')
<form action="/invoice" method="POST" enctype="multipart/form-data">
    {{-- will add route later --}}
    @csrf

    {{-- Alert Containers --}}
    <div class="max-w-7xl mx-auto px-4 mb-6">
            @if (session('success'))
                <div
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert"
                >
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">
                        {{ session('success') }}
                    </span>
                </div>
            @endif

            @if (session('error'))
                <div
                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                    role="alert"
                >
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">
                        {{ session('error') }}
                    </span>
                </div>
            @endif
        </div>

    {{-- Main Container --}}
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-x-8 gap-y-0 items-start">

        {{-- Left Side: Invoice Paper Form --}}
        <div class="md:col-span-3 order-1 mb-8">
            @include('form')
        </div>

        {{-- Right Side: Sidebar --}}
        {{-- On mobile (sm), this will come after the table due to order-3 --}}
        <div class="md:col-span-1 order-3 md:order-2 md:row-span-2 sticky top-6">
            @include('sidebar')
        </div>

        {{-- Bottom Section: Table --}}
        {{-- On mobile (sm), this will come after the form due to order-2 --}}
        <div class="md:col-span-3 order-2 md:order-3 md:col-start-1">
            @include('table')
        </div>

    </div>
</form>
@endsection



