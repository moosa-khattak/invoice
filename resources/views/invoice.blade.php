@extends('layout.layout')

@section('content')
    <form action="/invoice" method="POST" enctype="multipart/form-data">
        {{-- will add route later --}}
        @csrf

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
