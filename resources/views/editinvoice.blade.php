@extends('layout.layout')

@section('content')
    <form
        action="{{ route('invoice.update', $invoice->invoice_number) }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')

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
        </div>

        {{-- Main Container --}}
        <div
            class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row gap-8 items-start"
        >
            {{-- Left Side: Invoice Paper Form --}}
            @include('form')

            {{-- Right Side: Sidebar --}}
            @include('sidebar')
        </div>

        {{-- Bottom Section: Table --}}
        <div class="mt-8">
            @include('table')
        </div>
    </form>
@endsection
