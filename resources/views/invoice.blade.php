@extends('layout.layout')

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

                <!-- @if ($errors->any())
                    <div
                        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                        role="alert"
                    >
                        <strong class="font-bold">
                            Whoops! There were some problems with your input.
                        </strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif -->
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
